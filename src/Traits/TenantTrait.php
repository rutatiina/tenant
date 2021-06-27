<?php

namespace Rutatiina\Tenant\Traits;

use Illuminate\Support\Facades\Auth;
use Rutatiina\FinancialAccounting\Models\Account;
use Rutatiina\Tax\Models\Tax;
use Rutatiina\Tenant\Models\Tenant;

use Rutatiina\CashSale\Models\CashSaleSetting;
use Rutatiina\Bill\Models\BillSetting;
use Rutatiina\Bill\Models\RecurringBillSetting;
use Rutatiina\CreditNote\Models\CreditNoteSetting;
use Rutatiina\DebitNote\Models\DebitNoteSetting;
use Rutatiina\Estimate\Models\EstimateSetting;
use Rutatiina\Expense\Models\ExpenseSetting;
use Rutatiina\Expense\Models\RecurringExpenseSetting;
use Rutatiina\GoodsDelivered\Models\GoodsDeliveredSetting;
use Rutatiina\GoodsIssued\Models\GoodsIssuedSetting;
use Rutatiina\GoodsReceived\Models\GoodsReceivedSetting;
use Rutatiina\GoodsReturned\Models\GoodsReturnedSetting;
use Rutatiina\Invoice\Models\InvoiceSetting;
use Rutatiina\Invoice\Models\RecurringInvoiceSetting;
use Rutatiina\PaymentMade\Models\PaymentMadeSetting;
use Rutatiina\PurchaseOrder\Models\PurchaseOrderSetting;
use Rutatiina\PaymentReceived\Models\PaymentReceivedSetting;
use Rutatiina\RetainerInvoice\Models\RetainerInvoiceSetting;
use Rutatiina\SalesOrder\Models\SalesOrderSetting;

trait TenantTrait
{
    public static $tenant = null;

    public static function tenant()
    {
    	if (is_null(static::$tenant)) {
			static::$tenant = Tenant::find(Auth::user()->tenant->id);
		}
    }

    private function tenantSetup($tenant_id)
    {
        //create the tenants accounts
        $this->setupFinancialAccounts($tenant_id);

        //create tenants taxes
        Tax::insert(
            [
                [
                    'tenant_id' => $tenant_id,
                    'created_by' => NULL,
                    'country' => NULL,
                    'name' => 'VAT 18%',
                    'code' => 'vat',
                    'display_name' => 'VAT 18%',
                    'value' => '18%',
                    'based_on' => 'item',
                    'inclusive' => 1,
                    'on_sale' => 0,
                    'on_sale_effect' => 'credit',
                    'on_sale_financial_account_code' => 303,
                    'on_bill' => 0,
                    'on_bill_effect' => 'debit',
                    'on_bill_financial_account_code' => 302,
                ],
                [
                    'tenant_id' => $tenant_id,
                    'created_by' => NULL,
                    'country' => NULL,
                    'name' => 'Withholding Tax',
                    'code' => 'withholding-tax',
                    'display_name' => 'WHT (15%)',
                    'value' => '15%',
                    'based_on' => 'total',
                    'inclusive' => 0,
                    'on_sale' => 0,
                    'on_sale_effect' => 'credit',
                    'on_sale_financial_account_code' => 306,
                    'on_bill' => 0,
                    'on_bill_effect' => 'debit',
                    'on_bill_financial_account_code' => 305,
                ],
                [
                    'tenant_id' => $tenant_id,
                    'created_by' => NULL,
                    'country' => NULL,
                    'name' => 'Excise Duty 15%',
                    'code' => 'excise-duty',
                    'display_name' => 'Excise Duty 15%',
                    'value' => '15%',
                    'based_on' => 'total',
                    'inclusive' => 0,
                    'on_sale' => 0,
                    'on_sale_effect' => 'credit',
                    'on_sale_financial_account_code' => 381,
                    'on_bill' => 0,
                    'on_bill_effect' => 'debit',
                    'on_bill_financial_account_code' => 379,
                ]
            ]
        );

        //create the settings per transaction to each tenant

        if(class_exists('Rutatiina\Estimate\Models\EstimateSetting'))
        {
            EstimateSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Quotation',
                'document_type' => null,
                'financial_account_code' => 28,
            ]);
        }

        if(class_exists('Rutatiina\RetainerInvoice\Models\RetainerInvoiceSetting'))
        {
            RetainerInvoiceSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Invoice',
                'document_type' => 'invoice',
                'number_prefix' => 'RET-',
                'debit_financial_account_code' => 1, //receviables
                'credit_financial_account_code' => 70, //Deferred Revenue
            ]);
        }

        if(class_exists('Rutatiina\SalesOrder\Models\SalesOrderSetting'))
        {
            SalesOrderSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Sales Order',
                'document_type' => 'order',
            ]);
        }

        if(class_exists('Rutatiina\CashSale\Models\CashSaleSetting'))
        {
            CashSaleSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Invoice/Receipt',
                'document_type' => 'receipt',
                'debit_financial_account_code' => 3, //cash
                'credit_financial_account_code' => 2, //sales-revenue
            ]);
        }

        if(class_exists('Rutatiina\Bill\Models\BillSetting'))
        {
            BillSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Bill',
                'document_type' => 'bill',
                'debit_financial_account_code' => 5,
                'credit_financial_account_code' => 4,
            ]);
        }

        if(class_exists('Rutatiina\Bill\Models\RecurringBillSetting'))
        {
            RecurringBillSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Recurring Bill',
                'document_type' => null,
                'debit_financial_account_code' => 5,
                'credit_financial_account_code' => 4,
            ]);
        }

        if(class_exists('Rutatiina\CreditNote\Models\CreditNoteSetting'))
        {
            CreditNoteSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Credit Note',
                'document_type' => null,
                'debit_financial_account_code' => 69, //2,
                'credit_financial_account_code' => 1, //Receviables
            ]);
        }

        if(class_exists('Rutatiina\DebitNote\Models\DebitNoteSetting'))
        {
            DebitNoteSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Debit Note',
                'document_type' => null,
                'debit_financial_account_code' => 4,
                'credit_financial_account_code' => 68, //Purchase Returns
            ]);
        }

        if(class_exists('Rutatiina\Expense\Models\ExpenseSetting'))
        {
            ExpenseSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Payment Voucher',
                'document_type' => 'payment',
                'debit_financial_account_code' => 5,
                'credit_financial_account_code' => 3,
            ]);
        }

        if (class_exists('Rutatiina\Expense\Models\RecurringExpenseSetting'))
        {
            RecurringExpenseSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Recurring Expense',
                'document_type' => null,
                'debit_financial_account_code' => 5,
                'credit_financial_account_code' => 3,
            ]);
        }

        if (class_exists('Rutatiina\GoodsDelivered\Models\GoodsDeliveredSetting'))
        {
            GoodsDeliveredSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Delivered Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 54,
                'credit_financial_account_code' => 6,
            ]);
        }

        if (class_exists('Rutatiina\GoodsIssued\Models\GoodsIssuedSetting'))
        {
            GoodsIssuedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Issued Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 66,
                'credit_financial_account_code' => 6,
            ]);
        }

        if (class_exists('Rutatiina\GoodsReceived\Models\GoodsReceivedSetting'))
        {
            GoodsReceivedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Received Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 6,
                'credit_financial_account_code' => 0,
            ]);
        }

        if (class_exists('Rutatiina\GoodsReturned\Models\GoodsReturnedSetting'))
        {
            GoodsReturnedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Returned Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 6,
                'credit_financial_account_code' => 66,
            ]);
        }

        if (class_exists('Rutatiina\Invoice\Models\InvoiceSetting'))
        {
            InvoiceSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Invoice',
                'document_type' => 'invoice',
                'number_prefix' => 'INV-',
                'debit_financial_account_code' => 1,
                'credit_financial_account_code' => 2,
            ]);
        }

        if (class_exists('Rutatiina\Invoice\Models\RecurringInvoiceSetting'))
        {
            RecurringInvoiceSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Recurring Invoice',
                'document_type' => null,
                'debit_financial_account_code' => 1,
                'credit_financial_account_code' => 2,
            ]);
        }

        if (class_exists('Rutatiina\PaymentMade\Models\PaymentMadeSetting'))
        {
            PaymentMadeSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Payment Voucher',
                'document_type' => 'payment',
                'debit_financial_account_code' => 4,
                'credit_financial_account_code' => 3,
            ]);
        }

        if (class_exists('Rutatiina\PurchaseOrder\Models\PurchaseOrderSetting'))
        {
            PurchaseOrderSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Purchase Order',
                'document_type' => 'order',
                'financial_account_code' => 56,
            ]);
        }

        if (class_exists('Rutatiina\PaymentReceived\Models\PaymentReceivedSetting'))
        {
            PaymentReceivedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Receipt',
                'document_type' => 'receipt',
                'debit_financial_account_code' => 3,
                'credit_financial_account_code' => 1,
            ]);
        }

    }

    private function setupFinancialAccountTypes($tenant_id)
    {
        /*
         * BALANCE SHEET – ACCOUNTS TYPICALLY RESTRICTED TO FINANCE AND ACCOUNTING CORE OFFICES ONLY.
         * - Assets
         * - Liabilities
         * - Equity
         *
         * INCOME STATEMENT – ACCOUNTS USED TO RECORD FINANCIAL ACTIVITY IN DURING THE FISCAL YEAR.
         * - Operating Revenues
         * - Non-Operating Revenues
         * - Operating Expenses – Payroll
         * - Operating Expenses – Non-Payroll
         * – Non-Operating Expenses
         */
        $types = [
            //balance sheet *****************************

            //1XXXXX – Assets || 100-199
            'Assets' => [
                'Cash And Financial Assets' => [
                    'Cash and Cash Equivalents',
                    'Financial Assets (Investments)',
                    'Restricted Cash and Financial Assets',
                    'Additional Financial Assets and Investments',
                ],
                'Receivables And Contracts' => [
                    'Accounts Receivable', //Accounts, Notes And Loans Receivable
                    'Contracts',
                    'Nontrade And Other Receivables',
                ],
                'Inventory' => [
                    'Merchandise',
                    'Raw Material, Parts And Supplies',
                    'Work In Process',
                    'Finished Goods',
                    'Other Inventory'
                ],
                'Accruals And Additional Assets' => [
                    'Prepaid Expense',
                    'Accrued Income',
                    'Additional Assets'
                ],
                'Property, Plant And Equipment' => [
                    'Land And Land Improvements',
                    'Buildings, Structures And Improvements',
                    'Machinery And Equipment',
                    'Furniture And Fixtures',
                    'Additional Property, Plant And Equipment',
                    'Construction In Progress',
                ],
                'Intangible Assets (Excluding Goodwill)' => [
                    'Intellectual Property',
                    'Computer Software',
                    'Trade And Distribution Assets',
                    'Contracts And Rights',
                    'Right To Use Assets (Classified By Type)',
                    'Other Intangible Assets',
                    'Acquisition In Progress'
                ],
                'Goodwill' => [
                    'Goodwill'
                ],
            ],

            //2XXXXX – Liabilities || 200-299
            'Liabilities' => [
                'Payables' => [
                    'Accounts Payables',
                    'Dividends Payable',
                    'Interest Payable',
                    'Other Payables',
                ],
                'Accruals And Other Liabilities' => [
                    'Accrued Expenses',
                    'Deferred Income (Unearned Revenue) ',
                    'Accrued Taxes (Other Than Payroll)',
                    'Other (Non-Financial) Liabilities',
                ],
                'Financial Labilities' => [
                    'Notes Payable',
                    'Loans Payable',
                    'Bonds (Debentures)',
                    'Other Debts And Borrowings',
                    'Lease Obligations',
                    'Derivative Financial Liabilities',
                    'Other Liabilities',
                ],
                'Provisions (Contingencies)' => [
                    'Customer Related Provisions',
                    'Ligation And Regulatory Provisions',
                    'Other Provisions',
                ],
            ],

            //3XXXXX – Equity || 300-399
            'Equity' => [
                'Owners Equity (Attributable To Owners Of Parent)' => [
                    'Equity At par (Issued Capital)',
                    'Additional Paid-in Capital'
                ],
                'Retained Earnings' => [
                    'Appropriated',
                    'Unappropriated',
                    'Deficit',
                    'In Suspense'
                ],
                //'Accumulated OCI (US GAAP)' => [
                //    'Accumulated OCI (US GAAP)'
                //],
                //'Other Reserves (IFRS)' => [
                //    'Other Reserves (IFRS)'
                //],
                'Other Equity Items' => [
                    'ESOP Related Items',
                    'Subscribed Stock Receivables',
                    'Treasury Stock',
                    'Miscellaneous Equity'
                ],
                //'Noncontrolling (Minority) Interest' => [
                //    'Noncontrolling (Minority) Interest',
                //],
            ],

            //income statement ***************************

            //4XXXXX – Operating Revenues || 400-499
            'Operating Revenue' => [
                'Recognized Point Of Time' => [
                    'xxxx',
                    'xxxx'
                ],
                'Recognized Over Time' => [
                    'xxxx',
                    'xxxx'
                ],
                'Adjustments' => [
                    'xxxx',
                    'xxxx'
                ],
            ],

            //5XXXXX – Non-Operating Revenues
            'Non-Operating Revenue' => [
                'Other Revenue' => [
                    'Other Revenue'
                ],
                'Gains And Losses' => [
                    'Foreign Currency Transaction Gain (Loss)',
                    'Gain (Loss) On Investments',
                    'Gain (Loss) On Derivatives',
                    'Gain (Loss) On Disposal Of Assets',
                    'Debt Related Gain (Loss)',
                    'Impairment Loss',
                    'Other Gains And (Losses)',
                    'Other Revenue',
                ],
            ],

            //6XXXXX – Operating Expenses – Payroll
            'Operating Expenses – Payroll' => [
                'Payroll' => [
                    'Salaries and wages',
                    'Gross Salaries',
                    'Net Salary Control',
                ]
            ],

            //7XXXXX – Operating Expenses – Non-Payroll
            'Operating Expenses – Non-Payroll' => [
                'Expenses Classified By Nature' => [
                    'Material And Merchandise',
                    'Employee Benefits',
                    'Services',
                    'Amortization',
                    //'Increase (Decrease) In Inventories Of Finished Goods And Work In Progress',
                    //'Other Work Performed By Entity And Capitalized',


                    'Lodging',
                    'Office Supplies',
                    'Advertising And Marketing',
                    'Bank Fees and Charges',
                    'Credit Card Charges',
                    'Travel Expense',
                    'Telephone Expense',
                    'Automobile Expense',
                    'IT and Internet Expenses',
                    'Rent Expense',
                    'Janitorial Expense',
                    'Postage',
                    'Bad Debt',
                    'Printing and Stationery',
                    'Salaries and Employee Wages',
                    'Uncategorized',
                    'Meals and Entertainment',
                    'Depreciation Expense',
                    'Consultant Expense',
                    'Repairs and Maintenance',
                    'Other Expenses',
                ],
                'Expenses Classified By Function' => [
                    'Cost Of Sales',
                    'Selling, General And Administrative ',
                    'Accounts Receivable, Credit Loss (Reversal)',
                ],
            ],

            //8XXXXX – Non-Operating Expenses
            'Non-Operating Expenses' => [
                'Other Expenses' => [
                    'Other Expenses',
                ],
                'Taxes (Other Than Income And Payroll) And Fees' => [
                    'Real Estate Taxes And Insurance',
                    'Highway (Road) Taxes And Tolls',
                    'Direct Tax And License Fees',
                    'Excise And Sales Taxes',
                    'Customs Fees And Duties (Not Classified As Sales Or Excise)',
                    'Non-Deductible VAT (GST)',
                    'General Insurance Expense',
                    'Administrative Fees (Revenue Stamps)',
                    'Fines And Penalties',
                    'Miscellaneous Taxes',
                    'Other Taxes And Fees',
                ],
                'Income Tax Expense (Benefit)' => [
                    'Income Tax Expense (Benefit)',
                ],
            ],
        ];

        $type = [
            'asset' => [
                [
                    'title' => 'Assets',
                    'categories' => [
                        [
                            'name' => 'Cash And Financial Assets',
                            'accounts' => [
                                'Cash and Cash Equivalents',
                                'Financial Assets (Investments)',
                                'Restricted Cash and Financial Assets',
                                'Additional Financial Assets and Investments',
                            ]
                        ],
                        [
                            'name' => 'Receivables And Contracts',
                            'accounts' =>  [
                                'Accounts Receivable', //Accounts, Notes And Loans Receivable
                                'Contracts',
                                'Nontrade And Other Receivables',
                            ],
                        ],
                        [
                            'name' => 'Inventory',
                            'accounts' => [
                                'Merchandise',
                                'Raw Material, Parts And Supplies',
                                'Work In Process',
                                'Finished Goods',
                                'Other Inventory'
                            ],
                        ],
                        [
                            'name' => 'Accruals And Additional Assets',
                            'accounts' => [
                                'Prepaid Expense',
                                'Accrued Income',
                                'Additional Assets'
                            ],
                        ],
                        [
                            'name' => 'Property, Plant And Equipment',
                            'accounts' => [
                                'Land And Land Improvements',
                                'Buildings, Structures And Improvements',
                                'Machinery And Equipment',
                                'Furniture And Fixtures',
                                'Additional Property, Plant And Equipment',
                                'Construction In Progress',
                            ],
                        ],
                        [
                            'name' => 'Intangible Assets (Excluding Goodwill)',
                            'accounts' => [
                                'Intellectual Property',
                                'Computer Software',
                                'Trade And Distribution Assets',
                                'Contracts And Rights',
                                'Right To Use Assets (Classified By Type)',
                                'Other Intangible Assets',
                                'Acquisition In Progress'
                            ],
                        ],
                        [
                            'name' => 'Goodwill',
                            'accounts' => [
                                'Goodwill'
                            ],
                        ],
                    ],
                ]
            ],
            'liability' => [
                [
                    'title' => 'Liabilities',
                    'categories' => [
                        [
                            'name' => 'Payables',
                            'accounts' => [
                                'Accounts Payables',
                                'Dividends Payable',
                                'Interest Payable',
                                'Other Payables',
                            ]
                        ],
                        [
                            'name' => 'Accruals And Other Liabilities',
                            'accounts' => [
                                'Accrued Expenses',
                                'Deferred Income (Unearned Revenue) ',
                                'Accrued Taxes (Other Than Payroll)',
                                'Other (Non-Financial) Liabilities',
                            ]
                        ],
                        [
                            'name' => 'Financial Labilities',
                            'accounts' => [
                                'Notes Payable',
                                'Loans Payable',
                                'Bonds (Debentures)',
                                'Other Debts And Borrowings',
                                'Lease Obligations',
                                'Derivative Financial Liabilities',
                                'Other Liabilities',
                            ]
                        ],
                        [
                            'name' => 'Provisions (Contingencies)',
                            'accounts' => [
                                'Customer Related Provisions',
                                'Ligation And Regulatory Provisions',
                                'Other Provisions',
                            ]
                        ],
                    ]
                ]
            ],
            'equity' => [
                [
                    'title' => 'Equity',
                    'categories' => [
                        [
                            'name' => 'Owners Equity (Attributable To Owners Of Parent)',
                            'accounts' => [
                                'Equity At par (Issued Capital)',
                                'Additional Paid-in Capital'
                            ]
                        ],
                        [
                            'name' => 'Retained Earnings',
                            'accounts' => [
                                'Appropriated',
                                'Unappropriated',
                                'Deficit',
                                'In Suspense'
                            ]
                        ],
                        [
                            'name' => 'Other Equity Items',
                            'accounts' => [
                                'ESOP Related Items',
                                'Subscribed Stock Receivables',
                                'Treasury Stock',
                                'Miscellaneous Equity'
                            ]
                        ],
                    ]
                ]
            ],
            'revenue' => [
                [
                    'title' => 'Operating Revenue',
                    'categories' => [
                        [
                            'name' => '',
                            'accounts' => []
                        ],
                        [
                            'name' => '',
                            'accounts' => []
                        ],
                        [
                            'name' => '',
                            'accounts' => []
                        ],
                    ]
                ],
                [
                    'title' => 'Non-Operating Revenue',
                    'categories' => [
                        [
                            'name' => '',
                            'accounts' => []
                        ],
                    ]
                ],
            ],
            'expense' => [
                [
                    'title' => '',
                    'categories' => [
                        [
                            'name' => '',
                            'accounts' => []
                        ],
                    ]
                ]
            ],
        ];
    }

    private function setupFinancialAccounts($tenant_id)
    {
        //create the tenants accounts

        //<<Financial account
        Account::create([
                    'code' => 1,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'receivables',
                    'name' => 'Receivables',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 2,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'sales-revenue',
                    'name' => 'Sales Revenue',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 3,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'cash',
                    'name' => 'Cash',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 4,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'payables',
                    'name' => 'Payables',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 5,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'other-expenses',
                    'name' => 'Other Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 7,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'wallet',
                    'name' => 'Wallet',
                    'type' => 'liability',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 9,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'owners-equity',
                    'name' => 'Owner\'s Equity',
                    'type' => 'equity',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 10,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'paypal',
                    'name' => 'PayPal',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 11,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'yopayments',
                    'name' => 'YoPayments',
                    'type' => 'asset',
                    'sub_type' => 'current_asset',
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 13,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'capital',
                    'name' => 'Capital',
                    'type' => 'liability',
                    'sub_type' => 'long term liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 16,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'rent',
                    'name' => 'Rent',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 17,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'freight-and-transport',
                    'name' => 'Freight and Transport',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 18,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'meals',
                    'name' => 'Meals',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 19,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'accommodation',
                    'name' => 'Accommodation',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 20,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'stationery-and-printing',
                    'name' => 'Stationery and printing',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 21,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'electricity',
                    'name' => 'Electricity',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 22,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'water',
                    'name' => 'Water',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 23,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'salaries-and-wages',
                    'name' => 'Salaries and wages',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 24,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'repairs-and-maintenance',
                    'name' => 'Repairs and Maintenance',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 25,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'motor-vehicle-expenses',
                    'name' => 'Motor Vehicle Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 26,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'marketing',
                    'name' => 'Marketing',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 27,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'rave-payments',
                    'name' => 'Rave Payments',
                    'type' => 'asset',
                    'sub_type' => 'current_asset',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 30,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'purchases',
                    'name' => 'Purchases',
                    'type' => 'expense',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 31,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'discount-allowed',
                    'name' => 'Discount Allowed',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 33,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'entertainment',
                    'name' => 'Entertainment',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 52,
                    'tenant_id' => $tenant_id,
                    'parent_code' => NULL,
                    'slug' => 'discount-received',
                    'name' => 'Discount Received',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 53,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'opening-balance',
                    'name' => 'Opening Balance',
                    'type' => 'equity',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 54,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'cost-of-sales',
                    'name' => 'Cost of Sales',
                    'type' => 'cost_of_sales',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 67,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'petty-cash',
                    'name' => 'Petty Cash',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 68,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'purchase-returns',
                    'name' => 'Purchase Returns', //Purchase Returns Account is a contra-expense account; therefore, it can never have a debit balance. The balance will either be zero, or credit.
                    'type' => 'contra-expense',
                    'sub_type' => null,
                    'balance_type' => 'credit',
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 69,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'sales-returns',
                    'name' => 'Sales Returns', //The Sales Returns and Allowances account is a contra revenue account, meaning it opposes the revenue account from the initial purchase. You must debit the Sales Returns and Allowances account to show a decrease in revenue
                    'type' => 'contra-revenue',
                    'sub_type' => null,
                    'balance_type' => 'debit',
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 70,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'deferred-revenue',
                    'name' => 'Deferred Revenue', //this account is used by Retainer invoices
                    'type' => 'liability',
                    'sub_type' => null,
                    'balance_type' => 'both',
                    'description' => NULL,
                    'payment' => 1,
                ]);
        Account::create([
                    'code' => 72,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'fixed-assets',
                    'name' => 'Fixed Assets',
                    'type' => 'asset',
                    'sub_type' => 'fixed assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 73,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'gross-salaries',
                    'name' => 'Gross Salaries',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 74,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'employee-tax-control',
                    'name' => 'Employee Tax Control',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 75,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'net-salary-control',
                    'name' => 'Net Salary Control',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 76,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'employer-tax',
                    'name' => 'Employer Tax',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 77,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'employer-tax-control',
                    'name' => 'Employer Tax Control',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 78,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'salesperson',
                    'name' => 'SalesPerson',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 79,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'voluntary-deductions',
                    'name' => 'Voluntary Deductions',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 80,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'nssf-deduction',
                    'name' => 'NSSF deduction',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 81,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 80,
                    'slug' => 'employer-contribution',
                    'name' => 'Employer Contribution',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 82,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 80,
                    'slug' => 'employee-contribution',
                    'name' => 'Employee Contribution',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 83,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'paye-payable',
                    'name' => 'PAYE payable',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 84,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'salary-payables',
                    'name' => 'Salary Payables',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 85,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'nssf-employer-contribution',
                    'name' => 'NSSF employer contribution',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 89,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'administrative-expenses',
                    'name' => 'Administrative Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 90,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'foreign-exchange-gain',
                    'name' => 'Foreign Exchange Gain',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 91,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'foreign-exchange-loss',
                    'name' => 'Foreign Exchange loss',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 92,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'local-service-tax',
                    'name' => 'Local service tax',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 93,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'hrm-app-expenses',
                    'name' => 'HRM App Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 94,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'crm-app-expenses',
                    'name' => 'CRM App Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 145,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'operating-expenses',
                    'name' => 'Operating Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 146,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'advertisement',
                    'name' => 'Advertisement',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 147,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'audit-expenses',
                    'name' => 'Audit Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 148,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'bad-debts-written-off',
                    'name' => 'Bad Debts Written Off',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 149,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'computer-expenses',
                    'name' => 'Computer Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 150,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'consumption-of-stores-and-spare-parts',
                    'name' => 'Consumption of Stores and Spare Parts',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 151,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'conveyance-expenses',
                    'name' => 'Conveyance Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 152,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'gift-expenses',
                    'name' => 'Gift Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 153,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'hotel-boarding-and-lodging-expenses',
                    'name' => 'Hotel, Boarding and Lodging Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 154,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'legal-expenses',
                    'name' => 'Legal Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 155,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'power-and-fuel',
                    'name' => 'Power and Fuel',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 156,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'repairs-of-building',
                    'name' => 'Repairs of Building',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 157,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'repairs-of-machinery',
                    'name' => 'Repairs of Machinery',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 158,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'sales-promotion-including-publicity',
                    'name' => 'Sales Promotion including Publicity',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 159,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'staff-welfare-expenses',
                    'name' => 'Staff Welfare Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 160,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'startup-cost-pre-operating-expenses',
                    'name' => 'Startup cost/ pre- operating expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 162,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'subsistence-allowance',
                    'name' => 'Subsistence Allowance',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 164,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'telephone-expenses',
                    'name' => 'Telephone Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 165,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'training-expenditure',
                    'name' => 'Training Expenditure',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 166,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'traveling-expenses-including-foreign-traveling',
                    'name' => 'Traveling Expenses including foreign traveling',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 167,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'work-shop-conference-expenses',
                    'name' => 'Work Shop - Conference Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 168,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'management-fees',
                    'name' => 'Management Fees',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 169,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'scientific-research-expenses',
                    'name' => 'Scientific Research Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 170,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'employment-expenses',
                    'name' => 'Employment Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 171,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'bonus',
                    'name' => 'Bonus',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 172,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'reimbursement-of-medical-expenses',
                    'name' => 'Reimbursement of medical expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 173,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'leave-encashment',
                    'name' => 'Leave encashment',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 174,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'leave-travel-benefits',
                    'name' => 'Leave travel benefits',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 175,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'housing-allowancerent',
                    'name' => 'Housing allowance/rent',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 176,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'contribution-to-retirement-fund',
                    'name' => 'Contribution to retirement fund',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 177,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'contribution-to-any-other-fund',
                    'name' => 'Contribution to any other fund',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 178,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'financing-expenses',
                    'name' => 'Financing Expenses',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 179,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 178,
                    'slug' => 'interest-expense',
                    'name' => 'Interest Expense',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 180,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 178,
                    'slug' => 'bank-charges',
                    'name' => 'Bank charges',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 181,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 178,
                    'slug' => 'commitment-fees',
                    'name' => 'Commitment fees',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 182,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 178,
                    'slug' => 'insurance',
                    'name' => 'Insurance',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 183,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 178,
                    'slug' => 'realized-exchange-loss',
                    'name' => 'Realized exchange loss',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 184,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 178,
                    'slug' => 'unrealized-exchange-loss',
                    'name' => 'Unrealized exchange loss',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 185,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'dividend',
                    'name' => 'Dividend',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 186,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'gross-rental-income',
                    'name' => 'Gross Rental Income',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 187,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'net-trading-income',
                    'name' => 'Net Trading Income',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 188,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'annuity',
                    'name' => 'Annuity',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 189,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'natural-resource-payments',
                    'name' => 'Natural resource payments',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 190,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'royalties',
                    'name' => 'Royalties',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 191,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'profit-on-disposal-of-assets',
                    'name' => 'Profit on disposal of assets',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 192,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'interest-income',
                    'name' => 'Interest Income',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 194,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'gift-in-connection-with-the-use-or-exploitation-of',
                    'name' => 'Gift in connection with the use or exploitation of',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 195,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'realized-exchange-gain',
                    'name' => 'Realized exchange gain',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 196,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'unrealized-exchange-again',
                    'name' => 'Unrealized exchange again',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 197,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'provision-for-bad-and-doubtful-debtsimpairment-fo',
                    'name' => 'Provision for Bad and Doubtful Debts/Impairment fo',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 198,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'provision-for-bad-and-doubtful-debtsimpairment-fo',
                    'name' => 'Provision for Bad and Doubtful Debts/Impairment fo',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 199,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'rates',
                    'name' => 'Rates',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 261,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'accumulated-depreciation',
                    'name' => 'Accumulated Depreciation',
                    'type' => 'asset',
                    'sub_type' => 'fixed assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 269,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'mtn-mobile-money',
                    'name' => 'MTN Mobile Money',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 274,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'taxes-and-duties',
                    'name' => 'Taxes and Duties',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 275,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'donation-income',
                    'name' => 'Donation Income',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 276,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'donation-expense',
                    'name' => 'Donation Expense',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 277,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'depreciation-expense',
                    'name' => 'Depreciation Expense',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 302,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'vat-18-input',
                    'name' => 'VAT 18% Input',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 303,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'vat-18-output',
                    'name' => 'VAT 18% Output',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 305,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'withholding-tax-input',
                    'name' => 'Withholding tax Input',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 306,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'withholding-tax-output',
                    'name' => 'Withholding tax Output',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 314,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'accrued-warranty-liability',
                    'name' => 'Accrued Warranty Liability',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 315,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'warranty-expense',
                    'name' => 'Warranty Expense',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 317,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'warranty-assets',
                    'name' => 'Warranty Assets',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 318,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'gain-on-disposal',
                    'name' => 'Gain on disposal',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 319,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 89,
                    'slug' => 'loss-on-disposal-of-assets',
                    'name' => 'Loss on disposal of assets',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 320,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'loans-short-term',
                    'name' => 'Loans - Short Term',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 321,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'loans-long-term',
                    'name' => 'Loans - Long Term',
                    'type' => 'liability',
                    'sub_type' => 'long term liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 326,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'pesapal',
                    'name' => 'Pesapal',
                    'type' => 'asset',
                    'sub_type' => 'current assets',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 345,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'forex-exchange-transfers',
                    'name' => 'Forex Exchange transfers',
                    'type' => 'asset',
                    'sub_type' => 'adjustments',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 364,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'cost-of-goods-bought',
                    'name' => 'Cost of goods bought',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 371,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 145,
                    'slug' => 'commission-expense',
                    'name' => 'Commission expense',
                    'type' => 'expense',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 372,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'commission-payable',
                    'name' => 'Commission payable',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 374,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'commission-and-fees',
                    'name' => 'Commission and Fees',
                    'type' => 'income',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 375,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'stock-accrued',
                    'name' => 'Stock accrued',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 379,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'excise-duty-input',
                    'name' => 'Excise Duty Input',
                    'type' => 'expense',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 381,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'excise-duty-output',
                    'name' => 'Excise Duty Output',
                    'type' => 'liability',
                    'sub_type' => 'current liability',
                    'description' => NULL,
                    'payment' => 0,
                ]);
        //<<Financial account

    }
}
