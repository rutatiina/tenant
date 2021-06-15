<?php

namespace Rutatiina\Tenant\Traits;

use Illuminate\Support\Facades\Auth;
use Rutatiina\FinancialAccounting\Models\Account;
use Rutatiina\Tax\Models\Tax;
use Rutatiina\Tenant\Models\Tenant;

use Rutatiina\CashSale\Models\CashSaleSetting;
use Rutatiina\Bill\Models\BillSetting;
use Rutatiina\Bill\Models\RecurringBillSetting;
use Rutatiina\CreditNote\Models\Setting as CreditNoteSetting;
use Rutatiina\DebitNote\Models\Setting as DebitNoteSetting;
use Rutatiina\Estimate\Models\Setting as EstimateSetting;
use Rutatiina\Expense\Models\Setting as ExpenseSetting;
use Rutatiina\Expense\Models\RecurringExpenseSetting;
use Rutatiina\GoodsDelivered\Models\Setting as GoodsDeliveredSetting;
use Rutatiina\GoodsIssued\Models\Setting as GoodsIssuedSetting;
use Rutatiina\GoodsReceived\Models\Setting as GoodsReceivedSetting;
use Rutatiina\GoodsReturned\Models\Setting as GoodsReturnedSetting;
use Rutatiina\Invoice\Models\InvoiceSetting;
use Rutatiina\Invoice\Models\RecurringInvoiceSetting;
use Rutatiina\PaymentMade\Models\PaymentMadeSetting;
use Rutatiina\PurchaseOrder\Models\Setting as PurchaseOrderSetting;
use Rutatiina\PaymentReceived\Models\PaymentReceivedSetting;
use Rutatiina\RetainerInvoice\Models\Setting as RetainerInvoiceSetting;
use Rutatiina\SalesOrder\Models\Setting as SalesOrderSetting;

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
                    'code' => 6,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'inventory',
                    'name' => 'Inventory',
                    'type' => 'inventory',
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
                    'code' => 12,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'orders',
                    'name' => 'Orders',
                    'type' => 'none',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
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
                    'code' => 15,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'offers',
                    'name' => 'Offers',
                    'type' => 'none',
                    'sub_type' => NULL,
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
                    'code' => 28,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'quotation',
                    'name' => 'Quotation',
                    'type' => 'none',
                    'sub_type' => NULL,
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
                    'code' => 41,
                    'tenant_id' => $tenant_id,
                    'parent_code' => NULL,
                    'slug' => 'pro-forma-invoices',
                    'name' => 'Pro-forma Invoice(s)',
                    'type' => 'none',
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
                    'code' => 55,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 54,
                    'slug' => 'stock-adjustment',
                    'name' => 'Stock Adjustment',
                    'type' => '',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 56,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'purchase-order',
                    'name' => 'Purchase Order',
                    'type' => 'none',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 57,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 6,
                    'slug' => 'goods-in-transit',
                    'name' => 'Goods In Transit',
                    'type' => 'inventory',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 58,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'physical-stock',
                    'name' => 'Physical Stock',
                    'type' => 'none',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 66,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 6,
                    'slug' => 'salesperson-inventory',
                    'name' => 'SalesPerson Inventory',
                    'type' => 'inventory',
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
                    'code' => 144,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'actual-cash-received',
                    'name' => 'Actual Cash Received',
                    'type' => 'none',
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
                    'code' => 264,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'pending-transactions',
                    'name' => 'Pending Transactions',
                    'type' => 'none',
                    'sub_type' => NULL,
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
                    'code' => 273,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 6,
                    'slug' => 'inventory-reserve',
                    'name' => 'Inventory Reserve ',
                    'type' => 'inventory',
                    'sub_type' => NULL,
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
                    'code' => 316,
                    'tenant_id' => $tenant_id,
                    'parent_code' => 6,
                    'slug' => 'damages',
                    'name' => 'Damages',
                    'type' => 'inventory',
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
        Account::create([
                    'code' => 820,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'branches',
                    'name' => 'Branches',
                    'type' => 'inventory',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);
        Account::create([
                    'code' => 821,
                    'tenant_id' => $tenant_id,
                    'parent_code' => null,
                    'slug' => 'stores',
                    'name' => 'Stores',
                    'type' => 'inventory',
                    'sub_type' => NULL,
                    'description' => NULL,
                    'payment' => 0,
                ]);



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

        EstimateSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Quotation',
            'document_type' => null,
            'financial_account_code' => 28,
        ]);

        RetainerInvoiceSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Invoice',
            'document_type' => 'invoice',
            'number_prefix' => 'RET-',
            'debit_financial_account_code' => 1, //receviables
            'credit_financial_account_code' => 70, //Deferred Revenue
        ]);

        SalesOrderSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Sales Order',
            'document_type' => 'order',
            'financial_account_code' => 12,
        ]);

        CashSaleSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Invoice/Receipt',
            'document_type' => 'receipt',
            'debit_financial_account_code' => 3, //cash
            'credit_financial_account_code' => 2, //sales-revenue
        ]);

        BillSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Bill',
            'document_type' => 'bill',
            'debit_financial_account_code' => 5,
            'credit_financial_account_code' => 4,
        ]);

        RecurringBillSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Recurring Bill',
            'document_type' => null,
            'debit_financial_account_code' => 5,
            'credit_financial_account_code' => 4,
        ]);

        CreditNoteSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Credit Note',
            'document_type' => null,
            'debit_financial_account_code' => 69, //2,
            'credit_financial_account_code' => 1, //Receviables
        ]);

        DebitNoteSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Debit Note',
            'document_type' => null,
            'debit_financial_account_code' => 4,
            'credit_financial_account_code' => 68, //Purchase Returns
        ]);

        ExpenseSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Payment Voucher',
            'document_type' => 'payment',
            'debit_financial_account_code' => 5,
            'credit_financial_account_code' => 3,
        ]);

        RecurringExpenseSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Recurring Expense',
            'document_type' => null,
            'debit_financial_account_code' => 5,
            'credit_financial_account_code' => 3,
        ]);

        GoodsDeliveredSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Goods Delivered Note',
            'document_type' => 'inventory',
            'debit_financial_account_code' => 54,
            'credit_financial_account_code' => 6,
        ]);

        GoodsIssuedSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Goods Issued Note',
            'document_type' => 'inventory',
            'debit_financial_account_code' => 66,
            'credit_financial_account_code' => 6,
        ]);

        GoodsReceivedSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Goods Received Note',
            'document_type' => 'inventory',
            'debit_financial_account_code' => 6,
            'credit_financial_account_code' => 0,
        ]);

        GoodsReturnedSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Goods Returned Note',
            'document_type' => 'inventory',
            'debit_financial_account_code' => 6,
            'credit_financial_account_code' => 66,
        ]);

        InvoiceSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Invoice',
            'document_type' => 'invoice',
            'number_prefix' => 'INV-',
            'debit_financial_account_code' => 1,
            'credit_financial_account_code' => 2,
        ]);

        RecurringInvoiceSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Recurring Invoice',
            'document_type' => null,
            'debit_financial_account_code' => 1,
            'credit_financial_account_code' => 2,
        ]);

        PaymentMadeSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Payment Voucher',
            'document_type' => 'payment',
            'debit_financial_account_code' => 4,
            'credit_financial_account_code' => 3,
        ]);

        PurchaseOrderSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Purchase Order',
            'document_type' => 'order',
            'financial_account_code' => 56,
        ]);

        PaymentReceivedSetting::create([
            'tenant_id' => $tenant_id,
            'document_name' => 'Receipt',
            'document_type' => 'receipt',
            'debit_financial_account_code' => 3,
            'credit_financial_account_code' => 1,
        ]);

    }
}
