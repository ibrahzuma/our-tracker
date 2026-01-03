<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class SMSParserController extends Controller
{
    public function index()
    {
        return view('sms-parser.index');
    }

    public function parse(Request $request)
    {
        $request->validate(['sms_text' => 'required|string']);
        $text = $request->sms_text;
        
        $data = [
            'amount' => null,
            'date' => date('Y-m-d'),
            'type' => 'expense', // default
            'source' => 'mobile_money',
            'provider' => 'M-Pesa',
            'description' => '',
            'category' => 'General',
        ];

        // Pattern 1: Withdraw (Expense) - Agent Number extraction
        // "CLP7LX... Withdraw Tsh50,000.00 from 125897 - ABUBAKARI SEFU..."
        if (preg_match('/Confirmed\.?\s*On\s*(\d{1,2}\/\d{1,2}\/\d{2,4}).*?Withdraw\s*Tsh([\d,.]+)\s*from\s*(\d+)\s*-\s*(.+?)\s*Total fee/i', $text, $matches)) {
            $data['amount'] = $this->cleanAmount($matches[2]);
            $data['date'] = $this->parseDate($matches[1]);
            // Format: "Withdraw from AgentName (AgentNo)"
            $data['description'] = 'Withdraw from ' . trim($matches[4]) . ' (' . $matches[3] . ')';
            $data['type'] = 'expense';
            $data['category'] = 'Other';
        }
        
        // Pattern 2: Receive (Income)
        // "CLQ8LXL... Receive Tsh200,000.00 from JUSTAR JULIUS KYEBEREKA New balance..."
        // (Note: Receive messages often don't show the sender's phone number in the body, just name)
        elseif (preg_match('/Confirmed\.?\s*On\s*(\d{1,2}\/\d{1,2}\/\d{2,4}).*?Receive\s*Tsh([\d,.]+)\s*from\s*(.+?)\s*(?:New balance|Send or receive)/i', $text, $matches)) {
            $data['amount'] = $this->cleanAmount($matches[2]);
            $data['date'] = $this->parseDate($matches[1]);
            $data['description'] = 'Received from ' . trim($matches[3]);
            $data['type'] = 'income';
            $data['category'] = 'Other';
        }

        // Pattern 3: Send (Expense) - Account/Phone extraction
        // "CLP8LX... Tsh10,000.00 sent to TIPS-AIRTELMONEY for account 255689979621 on 25/12/25..."
        elseif (preg_match('/Confirmed\.?\s*Tsh([\d,.]+)\s*sent to\s*(.+?)\s*for account\s*(\d+)\s*on\s*(\d{1,2}\/\d{1,2}\/\d{2,4})/i', $text, $matches)) {
            $data['amount'] = $this->cleanAmount($matches[1]);
            $data['date'] = $this->parseDate($matches[4]);
            // Format: "Sent to Name (Phone)"
            $data['description'] = 'Sent to ' . trim($matches[2]) . ' (' . $matches[3] . ')';
            $data['type'] = 'expense';
            $data['category'] = 'Other';
        }
        
        // Generic Fallback (original simple patterns if specific ones fail)
        elseif (preg_match('/Confirmed\.?\s*Tsh\s*([\d,]+)\s*sent to\s*(.+?)\s*on\s*(\d{1,2}\/\d{1,2}\/\d{2,4})/i', $text, $matches)) {
             $data['amount'] = $this->cleanAmount($matches[1]);
             $data['description'] = 'Sent to ' . trim($matches[2]);
             $data['date'] = $this->parseDate($matches[3]);
        }

        // Clean Description
        if ($data['description']) {
           $data['description'] = preg_replace('/\s+/', ' ', $data['description']); 
           $data['description'] = str_replace('Total fee', '', $data['description']);
           $data['description'] = trim($data['description'], " .-,");
        }

        return view('sms-parser.preview', compact('data', 'text'));
    }

    private function cleanAmount($amountStr)
    {
        return filter_var($amountStr, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    private function parseDate($dateStr)
    {
        try {
            return Carbon::createFromFormat('d/m/y', $dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('d/m/Y', $dateStr)->format('Y-m-d');
            } catch (\Exception $e) {
                return date('Y-m-d');
            }
        }
    }
}
