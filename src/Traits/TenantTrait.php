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
                'credit_financial_account_code' => 220200, //Deferred Income (Unearned Revenue) -> old wrong entry 410200, //Deferred Revenue
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
                'credit_financial_account_code' => 130500, //Inventory [value was 6 before changing to codes]
            ]);
        }

        if (class_exists('Rutatiina\GoodsIssued\Models\GoodsIssuedSetting'))
        {
            GoodsIssuedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Issued Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 66, //sales person inventory
                'credit_financial_account_code' => 130500, //Inventory
            ]);
        }

        if (class_exists('Rutatiina\GoodsReceived\Models\GoodsReceivedSetting'))
        {
            GoodsReceivedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Received Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 130500, //Inventory
                'credit_financial_account_code' => 0,
            ]);
        }

        if (class_exists('Rutatiina\GoodsReturned\Models\GoodsReturnedSetting'))
        {
            GoodsReturnedSetting::create([
                'tenant_id' => $tenant_id,
                'document_name' => 'Goods Returned Note',
                'document_type' => 'inventory',
                'debit_financial_account_code' => 130500, //Inventory
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
                                [
                                    'name' => 'Petty cash',
                                    'code' => 110500
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
                                    'code' => 130200,
                                    'sub_type' => 'inventory'
                                ],
                                [
                                    'name' => 'Work In Process',
                                    'code' => 130300
                                ],
                                [
                                    'name' => 'Finished Goods',
                                    'code' => 130400,
                                    'sub_type' => 'inventory'
                                ],
                                [
                                    'name' => 'Inventory',
                                    'code' => 130500,
                                    'sub_type' => 'inventory'
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
                                //this is to be removed because 'Deferred Revenue' is a liabilitty thus 220200: Deferred Income (Unearned Revenue)
                                // [
                                //     'name' => 'Deferred Revenue',
                                //     'code' => 410200
                                // ],
                                [
                                    'name' => 'Interest Revenue',
                                    'code' => 410300
                                ],
                                [
                                    'name' => 'Late Fee Revenue',
                                    'code' => 410400
                                ],

                                //discount allowed and discount received accounts
                                [
                                    'name' => 'Discount allowed', //When the seller allows a discount, this is recorded as a reduction of revenues, and is typically a debit to a contra revenue account
                                    'code' => 410510,
                                    'sub_type' => 'contra_revenue'
                                ],
                                [
                                    'name' => 'Discount received', //A discount received is a revenue for any business concern.
                                    'code' => 410520
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
                                    'code' => 720100,
                                    'sub_type' => 'cost-of-sales'
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
                                    'code' => 720400,
                                    'sub_type' => 'contra-expense'
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
                    FinancialAccountCategory::withoutGlobalScopes()
                        ->updateOrCreate(
                            [
                                'code' => $category['code'],
                                'tenant_id' => $tenant_id,
                            ],
                            [
                                'type' => $type,
                                'title' => $title['title'],
                                'balance' => null,
                                'category_name' => $category['name']
                            ]
                        );

                    foreach ($category['accounts'] as $account)
                    {
                        Account::withoutGlobalScopes()
                            ->updateOrCreate(
                                [
                                    'code' => $account['code'],
                                    'tenant_id' => $tenant_id
                                ],
                                [
                                    'name' => $account['name'],
                                    'type' => $type,
                                    'sub_type' => (isset($account['sub_type'])) ? $account['sub_type'] : $type,
                                    'financial_account_category_code' => $category['code'],
                                    'payment' => @$account['payment'],
                                    //'balance' => NULL, //debit / credit / both
                                    //'description' => NULL,
                                    //'payment' => 0,
                                ]
                            );
                    }
                }
            }
        }
    }
}
