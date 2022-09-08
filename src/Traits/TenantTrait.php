<?php

namespace Rutatiina\Tenant\Traits;

use Illuminate\Support\Facades\Auth;
use Rutatiina\FinancialAccounting\Models\Account;
use Rutatiina\FinancialAccounting\Models\FinancialAccountCategory;
use Rutatiina\POS\Models\POSOrderSetting;
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
    	if (is_null(static::$tenant))
    	{
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

        if(class_exists('Rutatiina\POS\Models\POSOrderSetting'))
        {
            POSOrderSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Invoice/Receipt',
                'document_type' => 'receipt',
                'debit_financial_account_code' => 110100, //Cash and Cash Equivalents
                'credit_financial_account_code' => 410100, //Sales Revenue
            ]);
        }

        if(class_exists('Rutatiina\Estimate\Models\EstimateSetting'))
        {
            EstimateSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Quotation',
                'document_type' => null,
            ]);
        }

        if(class_exists('Rutatiina\RetainerInvoice\Models\RetainerInvoiceSetting'))
        {
            RetainerInvoiceSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Invoice',
                'document_type' => 'invoice',
                'number_prefix' => 'RET-',
                'debit_financial_account_code' => 120100, //Accounts Receviables
                'credit_financial_account_code' => 410200, //Deferred Revenue
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
                'debit_financial_account_code' => 110100, //Cash and Cash Equivalents
                'credit_financial_account_code' => 410100, //Sales Revenue
            ]);
        }

        if(class_exists('Rutatiina\Bill\Models\BillSetting'))
        {
            BillSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Bill',
                'document_type' => 'bill',
                'debit_financial_account_code' => 712700, //Other Expenses
                'credit_financial_account_code' => 210100, //Accounts Payables
            ]);
        }

        if(class_exists('Rutatiina\Bill\Models\RecurringBillSetting'))
        {
            RecurringBillSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Recurring Bill',
                'document_type' => null,
                'debit_financial_account_code' => 712700, //Other Expenses
                'credit_financial_account_code' => 210100, //Accounts Payables
            ]);
        }

        if(class_exists('Rutatiina\CreditNote\Models\CreditNoteSetting'))
        {
            CreditNoteSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Credit Note',
                'document_type' => null,
                'debit_financial_account_code' => 410800, //Sales Returns
                'credit_financial_account_code' => 120100, //Accounts Receviables
            ]);
        }

        if(class_exists('Rutatiina\DebitNote\Models\DebitNoteSetting'))
        {
            DebitNoteSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Debit Note',
                'document_type' => null,
                'debit_financial_account_code' => 210100, //Accounts Payables
                'credit_financial_account_code' => 720400, //Purchase Returns
            ]);
        }

        if(class_exists('Rutatiina\Expense\Models\ExpenseSetting'))
        {
            ExpenseSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Payment Voucher',
                'document_type' => 'payment',
                'debit_financial_account_code' => 712700, //Other Expenses
                'credit_financial_account_code' => 110100, //Cash and Cash Equivalents
            ]);
        }

        if (class_exists('Rutatiina\Expense\Models\RecurringExpenseSetting'))
        {
            RecurringExpenseSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Recurring Expense',
                'document_type' => null,
                'debit_financial_account_code' => 712700, //Other Expenses
                'credit_financial_account_code' => 110100, //Cash and Cash Equivalents
            ]);
        }

        //the bellow code is scheduled for delete since the details are in the respective packages services
        /*
        if (class_exists('Rutatiina\GoodsDelivered\Models\GoodsDeliveredSetting'))
        {
            GoodsDeliveredSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Delivered Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 720000, //Cost of Sales
                'credit_financial_account_code' => 130500, //Other Inventory [value was 6 before changing to codes]
            ]);
        }

        if (class_exists('Rutatiina\GoodsIssued\Models\GoodsIssuedSetting'))
        {
            GoodsIssuedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Issued Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 66, //sales person inventory
                'credit_financial_account_code' => 130500, //Other Inventory
            ]);
        }

        if (class_exists('Rutatiina\GoodsReceived\Models\GoodsReceivedSetting'))
        {
            GoodsReceivedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Received Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 130500, //Other Inventory
                'credit_financial_account_code' => 0,
            ]);
        }

        if (class_exists('Rutatiina\GoodsReturned\Models\GoodsReturnedSetting'))
        {
            GoodsReturnedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Returned Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 130500, //Other Inventory
                'credit_financial_account_code' => 66, //sales person inventory
            ]);
        }
        //*/

        if (class_exists('Rutatiina\Invoice\Models\InvoiceSetting'))
        {
            InvoiceSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Invoice',
                'document_type' => 'invoice',
                'number_prefix' => 'INV-',
                'debit_financial_account_code' => 120100, //Accounts Receviables
                'credit_financial_account_code' => 410100, //Sales Revenue
            ]);
        }

        if (class_exists('Rutatiina\Invoice\Models\RecurringInvoiceSetting'))
        {
            RecurringInvoiceSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Recurring Invoice',
                'document_type' => null,
                'debit_financial_account_code' => 120100, //Accounts Receviables
                'credit_financial_account_code' => 410100, //Sales Revenue
            ]);
        }

        if (class_exists('Rutatiina\PaymentMade\Models\PaymentMadeSetting'))
        {
            PaymentMadeSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Payment Voucher',
                'document_type' => 'payment',
                'debit_financial_account_code' => 210100, //Accounts Payables
                'credit_financial_account_code' => 110100, //Cash and Cash Equivalents
            ]);
        }

        if (class_exists('Rutatiina\PurchaseOrder\Models\PurchaseOrderSetting'))
        {
            PurchaseOrderSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Purchase Order',
                'document_type' => 'order',
            ]);
        }

        if (class_exists('Rutatiina\PaymentReceived\Models\PaymentReceivedSetting'))
        {
            PaymentReceivedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Receipt',
                'document_type' => 'receipt',
                'debit_financial_account_code' => 110100, //Cash and Cash Equivalents
                'credit_financial_account_code' => 120100, //Accounts Receviables
            ]);
        }

    }

    private function setupFinancialAccounts($tenant_id)
    {
        /*
         * BALANCE SHEET – ACCOUNTS TYPICALLY RESTRICTED TO FINANCE AND ACCOUNTING CORE OFFICES ONLY.
         * - 1XXXXX – Assets
         * - 2XXXXX – Liabilities
         * - 3XXXXX – Equity
         *
         * INCOME STATEMENT – ACCOUNTS USED TO RECORD FINANCIAL ACTIVITY IN DURING THE FISCAL YEAR.
         * - 4XXXXX – Operating Revenues
         * - 5XXXXX – Non-Operating Revenues
         * - 6XXXXX – Operating Expenses – Payroll
         * - 7XXXXX – Operating Expenses – Non-Payroll (Most common for requisitions, travel, PCard, etc.)
         * – 8XXXXX – Non-Operating Expenses
         */

        $types = [
            'asset' => [
                [
                    'title' => 'Assets',
                    'code' => 100000,
                    'categories' => [
                        [
                            'name' => 'Cash And Financial Assets',
                            'code' => 110000,
                            'accounts' => [
                                [
                                    'name' => 'Cash and Cash Equivalents',
                                    'code' => 110100,
                                    'payment' => 1
                                ],
                                [
                                    'name' => 'Financial Assets (Investments)',
                                    'code' => 110200
                                ],
                                [
                                    'name' => 'Restricted Cash and Financial Assets',
                                    'code' => 110300
                                ],
                                [
                                    'name' => 'Additional Financial Assets and Investments',
                                    'code' => 110400
                                ],
                            ]
                        ],
                        [
                            'name' => 'Receivables And Contracts',
                            'code' => 120000,
                            'accounts' =>  [
                                [
                                    'name' => 'Accounts Receivable',  //Accounts, Notes And Loans Receivable
                                    'code' => 120100
                                ],
                                [
                                    'name' => 'Contracts',
                                    'code' => 120200
                                ],
                                [
                                    'name' => 'Nontrade And Other Receivables',
                                    'code' => 120300
                                ],
                            ],
                        ],
                        [
                            'name' => 'Inventory',
                            'code' => 130000,
                            'accounts' => [
                                [
                                    'name' => 'Merchandise',
                                    'code' => 130100
                                ],
                                [
                                    'name' => 'Raw Material, Parts And Supplies',
                                    'code' => 130200
                                ],
                                [
                                    'name' => 'Work In Process',
                                    'code' => 130300
                                ],
                                [
                                    'name' => 'Finished Goods',
                                    'code' => 130400
                                ],
                                [
                                    'name' => 'Other Inventory',
                                    'code' => 130500
                                ]
                            ],
                        ],
                        [
                            'name' => 'Accruals And Additional Assets',
                            'code' => 140000,
                            'accounts' => [
                                [
                                    'name' => 'Prepaid Expense',
                                    'code' => 140100
                                ],
                                [
                                    'name' => 'Accrued Income',
                                    'code' => 140200
                                ],
                                [
                                    'name' => 'Additional Assets',
                                    'code' => 140300
                                ]
                            ],
                        ],
                        [
                            'name' => 'Property, Plant And Equipment',
                            'code' => 150000,
                            'accounts' => [
                                [
                                    'name' => 'Land And Land Improvements',
                                    'code' => 150100
                                ],
                                [
                                    'name' => 'Buildings, Structures And Improvements',
                                    'code' => 150200
                                ],
                                [
                                    'name' => 'Machinery And Equipment',
                                    'code' => 150300
                                ],
                                [
                                    'name' => 'Furniture And Fixtures',
                                    'code' => 150400
                                ],
                                [
                                    'name' => 'Additional Property, Plant And Equipment',
                                    'code' => 150500
                                ],
                                [
                                    'name' => 'Construction In Progress',
                                    'code' => 150600
                                ],
                            ],
                        ],
                        [
                            'name' => 'Intangible Assets (Excluding Goodwill)',
                            'code' => 160000,
                            'accounts' => [
                                [
                                    'name' => 'Intellectual Property',
                                    'code' => 160100
                                ],
                                [
                                    'name' => 'Computer Software',
                                    'code' => 160200
                                ],
                                [
                                    'name' => 'Trade And Distribution Assets',
                                    'code' => 160300
                                ],
                                [
                                    'name' => 'Contracts And Rights',
                                    'code' => 160400
                                ],
                                [
                                    'name' => 'Right To Use Assets (Classified By Type)',
                                    'code' => 160500
                                ],
                                [
                                    'name' => 'Other Intangible Assets',
                                    'code' => 160600
                                ],
                                [
                                    'name' => 'Acquisition In Progress',
                                    'code' => 160700
                                ],
                            ],
                        ],
                        [
                            'name' => 'Goodwill',
                            'code' => 170000,
                            'accounts' => [
                                [
                                    'name' => 'Goodwill',
                                    'code' => 170100
                                ],
                            ],
                        ],
                    ],
                ]
            ],
            'liability' => [
                [
                    'title' => 'Liabilities',
                    'code' => 200000,
                    'categories' => [
                        [
                            'name' => 'Payables',
                            'code' => 210000,
                            'accounts' => [
                                [
                                    'name' => 'Accounts Payables',
                                    'code' => 210100
                                ],
                                [
                                    'name' => 'Dividends Payable',
                                    'code' => 210200
                                ],
                                [
                                    'name' => 'Interest Payable',
                                    'code' => 210300
                                ],
                                [
                                    'name' => 'Other Payables',
                                    'code' => 210400
                                ],
                            ]
                        ],
                        [
                            'name' => 'Accruals And Other Liabilities',
                            'code' => 220000,
                            'accounts' => [
                                [
                                    'name' => 'Accrued Expenses',
                                    'code' => 220100
                                ],
                                [
                                    'name' => 'Deferred Income (Unearned Revenue)',
                                    'code' => 220200
                                ],
                                [
                                    'name' => 'Accrued Taxes (Other Than Payroll)',
                                    'code' => 220300
                                ],
                                [
                                    'name' => 'Other (Non-Financial) Liabilities',
                                    'code' => 220400
                                ],
                            ]
                        ],
                        [
                            'name' => 'Financial Labilities',
                            'code' => 230000,
                            'accounts' => [
                                [
                                    'name' => 'Notes Payable',
                                    'code' => 230100
                                ],
                                [
                                    'name' => 'Loans Payable',
                                    'code' => 230200
                                ],
                                [
                                    'name' => 'Bonds (Debentures)',
                                    'code' => 230300
                                ],
                                [
                                    'name' => 'Other Debts And Borrowings',
                                    'code' => 230400
                                ],
                                [
                                    'name' => 'Lease Obligations',
                                    'code' => 230500
                                ],
                                [
                                    'name' => 'Derivative Financial Liabilities',
                                    'code' => 230600
                                ],
                                [
                                    'name' => 'Other Liabilities',
                                    'code' => 230700
                                ],
                            ]
                        ],
                        [
                            'name' => 'Provisions (Contingencies)',
                            'code' => 240000,
                            'accounts' => [
                                [
                                    'name' => 'Customer Related Provisions',
                                    'code' => 240100
                                ],
                                [
                                    'name' => 'Ligation And Regulatory Provisions',
                                    'code' => 240200
                                ],
                                [
                                    'name' => 'Other Provisions',
                                    'code' => 240300
                                ],
                            ]
                        ],
                    ]
                ]
            ],
            'equity' => [
                [
                    'title' => 'Equity',
                    'code' => 300000,
                    'categories' => [
                        [
                            'name' => 'Owners Equity (Attributable To Owners Of Parent)',
                            'code' => 310000,
                            'accounts' => [
                                [
                                    'name' => 'Equity At par (Issued Capital)',
                                    'code' => 310100
                                ],
                                [
                                    'name' => 'Additional Paid-in Capital',
                                    'code' => 310200
                                ],
                            ]
                        ],
                        [
                            'name' => 'Retained Earnings',
                            'code' => 320000,
                            'accounts' => [
                                [
                                    'name' => 'Appropriated',
                                    'code' => 320100
                                ],
                                [
                                    'name' => 'Unappropriated',
                                    'code' => 320200
                                ],
                                [
                                    'name' => 'Deficit',
                                    'code' => 320300
                                ],
                                [
                                    'name' => 'In Suspense',
                                    'code' => 320400
                                ],
                            ]
                        ],
                        [
                            'name' => 'Other Equity Items',
                            'code' => 330000,
                            'accounts' => [
                                [
                                    'name' => 'ESOP Related Items',
                                    'code' => 330100
                                ],
                                [
                                    'name' => 'Subscribed Stock Receivables',
                                    'code' => 330200
                                ],
                                [
                                    'name' => 'Treasury Stock',
                                    'code' => 330300
                                ],
                                [
                                    'name' => 'Miscellaneous Equity',
                                    'code' => 330400
                                ],
                            ]
                        ],
                    ]
                ]
            ],
            'revenue' => [
                [
                    'title' => 'Revenue',
                    'code' => 400000,
                    'categories' => [
                        [
                            'name' => 'Revenue',
                            'code' => 410000,
                            'accounts' => [
                                [
                                    'name' => 'Sales Revenue',
                                    'code' => 410100
                                ],
                                [
                                    'name' => 'Deferred Revenue',
                                    'code' => 410200
                                ],
                                [
                                    'name' => 'Interest Revenue',
                                    'code' => 410300
                                ],
                                [
                                    'name' => 'Late Fee Revenue',
                                    'code' => 410400
                                ],
                                [
                                    'name' => 'Discount',
                                    'code' => 410500
                                ],
                                [
                                    'name' => 'Shipping Charge',
                                    'code' => 410600
                                ],
                                [
                                    'name' => 'Other Charges',
                                    'code' => 410700
                                ],
                                [
                                    'name' => 'Sales Returns', //is a contr account
                                    'code' => 410800
                                ],
                            ]
                        ],
                        [
                            'name' => 'Recognized Point Of Time',
                            'code' => 420000,
                            'accounts' => [
                                [
                                    'name' => 'Goods',
                                    'code' => 420100
                                ],
                                [
                                    'name' => 'Services',
                                    'code' => 420200
                                ],
                            ]
                        ],
                        [
                            'name' => 'Recognized Over Time',
                            'code' => 430000,
                            'accounts' => [
                                [
                                    'name' => 'Products',
                                    'code' => 430100
                                ],
                                [
                                    'name' => 'Services',
                                    'code' => 430200
                                ],
                            ]
                        ],
                        [
                            'name' => 'Adjustments',
                            'code' => 440000,
                            'accounts' => [
                                [
                                    'name' => 'Variable Consideration',
                                    'code' => 440100
                                ],
                                [
                                    'name' => 'Consideration Paid (Payable) To Customers',
                                    'code' => 440200
                                ],
                                [
                                    'name' => 'Other Adjustments',
                                    'code' => 440300
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'title' => 'Non-Operating Revenue',
                    'code' => 500000,
                    'categories' => [
                        [
                            'name' => 'Gains And Losses',
                            'code' => 510000,
                            'accounts' => [
                                [
                                    'name' => 'Foreign Currency Transaction Gain (Loss)',
                                    'code' => 510100
                                ],
                                [
                                    'name' => 'Gain (Loss) On Investments',
                                    'code' => 510200
                                ],
                                [
                                    'name' => 'Gain (Loss) On Derivatives',
                                    'code' => 510300
                                ],
                                [
                                    'name' => 'Gain (Loss) On Disposal Of Assets',
                                    'code' => 510400
                                ],
                                [
                                    'name' => 'Debt Related Gain (Loss)',
                                    'code' => 510500
                                ],
                                [
                                    'name' => 'Impairment Loss',
                                    'code' => 510600
                                ],
                                [
                                    'name' => 'Other Gains And (Losses)',
                                    'code' => 510700
                                ],
                            ]
                        ],
                    ]
                ],
            ],
            'expense' => [
                [
                    'title' => 'Operating Expenses – Payroll',
                    'code' => 600000,
                    'categories' => [
                        [
                            'name' => 'Payroll',
                            'code' => 610000,
                            'accounts' => [
                                [
                                    'name' => 'Salaries and wages',
                                    'code' => 610100
                                ],
                                [
                                    'name' => 'Gross Salaries',
                                    'code' => 610200
                                ],
                                [
                                    'name' => 'Net Salary Control',
                                    'code' => 610300
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'title' => 'Operating Expenses – Non-Payroll',
                    'code' => 700000,
                    'categories' => [
                        [
                            'name' => 'Expenses Classified By Nature',
                            'code' => 710000,
                            'accounts' => [
                                [
                                    'name' => 'Material And Merchandise',
                                    'code' => 710100
                                ],
                                [
                                    'name' => 'Employee Benefits',
                                    'code' => 710200
                                ],
                                [
                                    'name' => 'Services',
                                    'code' => 710300
                                ],
                                [
                                    'name' => 'Amortization',
                                    'code' => 710400
                                ],
                                [
                                    'name' => 'Increase (Decrease) In Inventories Of Finished Goods And Work In Progress',
                                    'code' => 710500
                                ],
                                [
                                    'name' => 'Other Work Performed By Entity And Capitalized',
                                    'code' => 710600
                                ],

                                [
                                    'name' => 'Lodging',
                                    'code' => 710700
                                ],
                                [
                                    'name' => 'Office Supplies',
                                    'code' => 710800
                                ],
                                [
                                    'name' => 'Advertising And Marketing',
                                    'code' => 710900
                                ],
                                [
                                    'name' => 'Bank Fees and Charges',
                                    'code' => 711000
                                ],
                                [
                                    'name' => 'Credit Card Charges',
                                    'code' => 711100
                                ],
                                [
                                    'name' => 'Travel Expense',
                                    'code' => 711200
                                ],
                                [
                                    'name' => 'Telephone Expense',
                                    'code' => 711300
                                ],
                                [
                                    'name' => 'Automobile Expense',
                                    'code' => 711400
                                ],
                                [
                                    'name' => 'IT and Internet Expenses',
                                    'code' => 711500
                                ],
                                [
                                    'name' => 'Rent Expense',
                                    'code' => 711600
                                ],
                                [
                                    'name' => 'Janitorial Expense',
                                    'code' => 711700
                                ],
                                [
                                    'name' => 'Postage',
                                    'code' => 711800
                                ],
                                [
                                    'name' => 'Bad Debt',
                                    'code' => 711900
                                ],
                                [
                                    'name' => 'Printing and Stationery',
                                    'code' => 712000
                                ],
                                [
                                    'name' => 'Salaries and Employee Wages',
                                    'code' => 712100
                                ],
                                [
                                    'name' => 'Uncategorized',
                                    'code' => 712200
                                ],
                                [
                                    'name' => 'Meals and Entertainment',
                                    'code' => 712300
                                ],
                                [
                                    'name' => 'Depreciation Expense',
                                    'code' => 712400
                                ],
                                [
                                    'name' => 'Consultant Expense',
                                    'code' => 712500
                                ],
                                [
                                    'name' => 'Repairs and Maintenance',
                                    'code' => 712600
                                ],
                                [
                                    'name' => 'Other Expenses',
                                    'code' => 712700
                                ],
                            ]
                        ],
                        [
                            'name' => 'Expenses Classified By Function',
                            'code' => 720000,
                            'accounts' => [
                                [
                                    'name' => 'Cost Of Sales',
                                    'code' => 720100
                                ],
                                [
                                    'name' => 'Selling, General And Administrative',
                                    'code' => 720200
                                ],
                                [
                                    'name' => 'Accounts Receivable, Credit Loss (Reversal)',
                                    'code' => 720300
                                ],
                                [
                                    'name' => 'Purchase Returns', //contra-expense account; therefore, it can never have a debit balance. The balance will either be zero or credit.
                                    'code' => 720400
                                ],
                            ]
                        ]
                    ]
                ],
                [
                    'title' => 'Non-Operating Expenses',
                    'code' => 800000,
                    'categories' => [
                        [
                            'name' => 'Other Expenses',
                            'code' => 810000,
                            'accounts' => [
                                [
                                    'name' => 'Other Expenses',
                                    'code' => 810100
                                ],
                            ]
                        ],
                        [
                            'name' => 'Taxes (Other Than Income And Payroll) And Fees',
                            'code' => 820000,
                            'accounts' => [
                                [
                                    'name' => 'Real Estate Taxes And Insurance',
                                    'code' => 820100
                                ],
                                [
                                    'name' => 'Highway (Road) Taxes And Tolls',
                                    'code' => 820200
                                ],
                                [
                                    'name' => 'Direct Tax And License Fees',
                                    'code' => 820300
                                ],
                                [
                                    'name' => 'Excise And Sales Taxes',
                                    'code' => 820400
                                ],
                                [
                                    'name' => 'Customs Fees And Duties (Not Classified As Sales Or Excise)',
                                    'code' => 820500
                                ],
                                [
                                    'name' => 'Non-Deductible VAT (GST)',
                                    'code' => 820600
                                ],
                                [
                                    'name' => 'General Insurance Expense',
                                    'code' => 820700
                                ],
                                [
                                    'name' => 'Administrative Fees (Revenue Stamps)',
                                    'code' => 820800
                                ],
                                [
                                    'name' => 'Fines And Penalties',
                                    'code' => 820900
                                ],
                                [
                                    'name' => 'Miscellaneous Taxes',
                                    'code' => 821000
                                ],
                                [
                                    'name' => 'Other Taxes And Fees',
                                    'code' => 821100
                                ],
                            ]
                        ],
                        [
                            'name' => 'Income Tax Expense (Benefit)',
                            'code' => 830000,
                            'accounts' => [
                                [
                                    'name' => 'Income Tax Expense (Benefit)',
                                    'code' => 830100
                                ],
                            ]
                        ],
                    ]
                ]
            ],
        ];

        foreach ($types as $type => $titles)
        {
            foreach ($titles as $title)
            {
                foreach ($title['categories'] as $category)
                {
                    FinancialAccountCategory::create([
                        'code' => $category['code'],
                        'tenant_id' => $tenant_id,
                        'type' => $type,
                        'title' => $title['title'],
                        'balance' => null,
                        'category_name' => $category['name']
                    ]);

                    foreach ($category['accounts'] as $account)
                    {
                        Account::create([
                            'code' => $account['code'],
                            'tenant_id' => $tenant_id,
                            'name' => $account['name'],
                            'type' => ($account['code'] == 720100) ? 'cost-of-sales' : $type,
                            'financial_account_category_code' => $category['code'],
                            'payment' => @$account['payment'],
                            //'balance' => NULL, //debit / credit / both
                            //'description' => NULL,
                            //'payment' => 0,
                        ]);
                    }
                }
            }
        }
    }

    private function _old__setupFinancialAccounts($tenant_id)
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
