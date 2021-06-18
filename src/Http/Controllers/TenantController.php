<?php

namespace Rutatiina\Tenant\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rutatiina\Banking\Models\Transaction;
use Rutatiina\Bill\Models\Bill;
use Rutatiina\Bill\Models\BillItem;
use Rutatiina\Bill\Models\BillItemTax;
use Rutatiina\Bill\Models\BillLedger;
use Rutatiina\Bill\Models\RecurringBill;
use Rutatiina\Bill\Models\RecurringBillItem;
use Rutatiina\Bill\Models\RecurringBillItemTax;
use Rutatiina\CashSale\Models\CashSale;
use Rutatiina\CashSale\Models\CashSaleItem;
use Rutatiina\CashSale\Models\CashSaleItemTax;
use Rutatiina\CashSale\Models\CashSaleLedger;
use Rutatiina\CreditNote\Models\CreditNote;
use Rutatiina\CreditNote\Models\CreditNoteItem;
use Rutatiina\CreditNote\Models\CreditNoteItemTax;
use Rutatiina\CreditNote\Models\CreditNoteLedger;
use Rutatiina\DebitNote\Models\DebitNote;
use Rutatiina\DebitNote\Models\DebitNoteItem;
use Rutatiina\DebitNote\Models\DebitNoteItemTax;
use Rutatiina\DebitNote\Models\DebitNoteLedger;
use Rutatiina\Estimate\Models\Estimate;
use Rutatiina\Estimate\Models\EstimateItem;
use Rutatiina\Estimate\Models\EstimateItemTax;
use Rutatiina\Expense\Models\Expense;
use Rutatiina\Expense\Models\ExpenseItem;
use Rutatiina\Expense\Models\ExpenseItemTax;
use Rutatiina\Expense\Models\ExpenseLedger;
use Rutatiina\Expense\Models\RecurringExpense;
use Rutatiina\Expense\Models\RecurringExpenseItem;
use Rutatiina\Expense\Models\RecurringExpenseItemTax;
use Rutatiina\FinancialAccounting\Models\Account;
use Rutatiina\FinancialAccounting\Models\AccountBalance;
use Rutatiina\FinancialAccounting\Models\ContactBalance;
use Rutatiina\GoodsDelivered\Models\GoodsDelivered;
use Rutatiina\GoodsDelivered\Models\GoodsDeliveredItem;
use Rutatiina\GoodsDelivered\Models\GoodsDeliveredLedger;
use Rutatiina\GoodsIssued\Models\GoodsIssued;
use Rutatiina\GoodsIssued\Models\GoodsIssuedItem;
use Rutatiina\GoodsIssued\Models\GoodsIssuedLedger;
use Rutatiina\GoodsReceived\Models\GoodsReceived;
use Rutatiina\GoodsReceived\Models\GoodsReceivedItem;
use Rutatiina\GoodsReceived\Models\GoodsReceivedLedger;
use Rutatiina\GoodsReturned\Models\GoodsReturned;
use Rutatiina\GoodsReturned\Models\GoodsReturnedItem;
use Rutatiina\GoodsReturned\Models\GoodsReturnedLedger;
use Rutatiina\Invoice\Models\Invoice;
use Rutatiina\Invoice\Models\InvoiceItem;
use Rutatiina\Invoice\Models\InvoiceItemTax;
use Rutatiina\Invoice\Models\InvoiceLedger;
use Rutatiina\Invoice\Models\RecurringInvoice;
use Rutatiina\Invoice\Models\RecurringInvoiceItem;
use Rutatiina\Invoice\Models\RecurringInvoiceItemTax;
use Rutatiina\PaymentMade\Models\PaymentMade;
use Rutatiina\PaymentMade\Models\PaymentMadeItem;
use Rutatiina\PaymentMade\Models\PaymentMadeItemTax;
use Rutatiina\PaymentMade\Models\PaymentMadeLedger;
use Rutatiina\PurchaseOrder\Models\PurchaseOrder;
use Rutatiina\PurchaseOrder\Models\PurchaseOrderItem;
use Rutatiina\PurchaseOrder\Models\PurchaseOrderItemTax;
use Rutatiina\PaymentReceived\Models\PaymentReceived;
use Rutatiina\PaymentReceived\Models\PaymentReceivedItem;
use Rutatiina\PaymentReceived\Models\PaymentReceivedItemTax;
use Rutatiina\PaymentReceived\Models\PaymentReceivedLedger;
use Rutatiina\RetainerInvoice\Models\RetainerInvoice;
use Rutatiina\RetainerInvoice\Models\RetainerInvoiceItem;
use Rutatiina\RetainerInvoice\Models\RetainerInvoiceItemTax;
use Rutatiina\RetainerInvoice\Models\RetainerInvoiceLedger;
use Rutatiina\SalesOrder\Models\SalesOrder;
use Rutatiina\SalesOrder\Models\SalesOrderItem;
use Rutatiina\SalesOrder\Models\SalesOrderItemTax;
use Rutatiina\Tenant\Models\Tenant;
use Rutatiina\Admin\Models\ServiceUser;
use Rutatiina\Tenant\Traits\TenantTrait;

class TenantController extends Controller
{
    use TenantTrait;

    public function __construct()
    {
        $this->middleware('tenant', ['only' => ['tenant', 'index', 'edit', 'deleteTxns', 'test']]);
    }

    public function tenant()
    {
        return Auth::user()->tenant;
    }

    private function database($id)
    {
        $db = 'rg_tenant_' . $id;
        $db_username = config('database.connections.system.username');

        $privileges = DB::select("SELECT Create_priv FROM mysql.user WHERE USER = '" . $db_username . "' ");

        if ($privileges[0]->Create_priv == 'Y')
        {
            //return 'user has permission';
        }
        else
        {
            return false; //'user does not have permission';
        }


        DB::statement('CREATE DATABASE IF NOT EXISTS `' . $db . '`');

        config(['database.connections.tenant.database' => $db]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        //return config('database.connections.tenant.database');

        config(['database.default' => 'tenant']); //change default connection to tenant

        #todo #rutatiina update the migration bellow to the current ones
        //Artisan::call('migrate', array('--path' => 'packages/rutatiina/financial-accounts/database/migrations'));
        //Artisan::call('migrate', array('--path' => 'packages/rutatiina/banking/database/migrations'));
        //Artisan::call('migrate', array('--path' => 'packages/rutatiina/contact/database/migrations'));
        //Artisan::call('migrate', array('--path' => 'packages/rutatiina/item/database/migrations'));

        return $db;
    }

    public function index(Request $request)
    {
        $per_page = ($request->per_page) ? $request->per_page : 20;

        if (!FacadesRequest::wantsJson())
        {
            return view('l-limitless-bs4.layout_2-ltr-default.appVue');
        }

        //get all services user has access to
        $services = Auth::user()->services;

        //get the tenant ids
        $tenantIds = [];
        foreach ($services as $service)
        {
            $tenantIds[] = $service->tenant_id;
        }

        return [
            'tableData' => Tenant::whereIn('id', $tenantIds)->paginate($per_page)
        ];
    }

    public function create()
    {
        if (!FacadesRequest::wantsJson())
        {
            return view('l-limitless-bs4.layout_2-ltr-default.appVue');
        }

        $Tenant = new Tenant;
        $attributes = $Tenant->rgGetAttributes();
        $attributes['_method'] = 'POST';

        //set the default attribute values
        $attributes['country'] = 'UG';
        $attributes['base_currency'] = 'UGX';
        $attributes['language'] = 'en';
        $attributes['time_zone'] = 'Africa/Kampala';
        $attributes['date_format'] = 'yyyy-dd-mm';


        $data = [
            'pageTitle' => 'Create Organisation',
            'urlPost' => '/settings/organisations', #required
            'attributes' => $attributes,
        ];

        if (FacadesRequest::wantsJson())
        {
            return $data;
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'service_id' => ['required', 'numeric'],
            //'email' => ['required', 'email', 'max:255'],
            'country' => 'required',
            'base_currency' => 'required|string',
        ]);

        if ($validator->fails())
        {
            return ['status' => false, 'messages' => $validator->errors()->all()];
        }

        $logo = null;
        if ($request->file('logo'))
        {
            $logo = Storage::disk('public_storage')->putFile('/', $request->file('logo'));
        }

        DB::beginTransaction();

        try
        {

            $tenant = new Tenant;

            $tenant->type = $request->type;
            $tenant->service_id = $request->service_id;
            //$tenant->agent_id             = $request->agent_id;
            $tenant->name = $request->name;
            $tenant->logo = $logo;
            $tenant->alias = $request->alias;
            //$tenant->email                  = $request->email;
            $tenant->industry = $request->industry;
            $tenant->country = $request->country;
            $tenant->street_line_1 = $request->street_line_1;
            $tenant->street_line_2 = $request->street_line_2;
            $tenant->city = $request->city;
            $tenant->state_province = $request->state_province;
            $tenant->zip_postal_code = $request->zip_postal_code;
            $tenant->phone = $request->phone;
            $tenant->fax = $request->fax;
            $tenant->website = $request->website;
            $tenant->base_currency = $request->base_currency;
            $tenant->fiscal_year = $request->fiscal_year;
            $tenant->fiscal_year_start = $request->fiscal_year_start;
            $tenant->language = $request->language;
            $tenant->time_zone = $request->time_zone;
            $tenant->date_format = $request->date_format;
            $tenant->company_id_name = $request->company_id_name;
            $tenant->company_id_value = $request->company_id_value;
            $tenant->tax_id_name = $request->tax_id_name;
            $tenant->tax_id_value = $request->tax_id_value;
            $tenant->status = $request->status;
            $tenant->sms_credits = $request->sms_credits;
            //$tenant->inventory_valuation_system = $request->inventory_valuation_system;
            //$tenant->inventory_valuation_method = $request->inventory_valuation_method;
            //$tenant->decimal_places       = $request->decimal_places;
            //$tenant->package_accounts     = $request->package_accounts;
            //$tenant->package_human_resource = $request->package_human_resource;

            $tenant->save();

            $tenant_id = $tenant->id;

            $ServiceUser = new ServiceUser;
            $ServiceUser->service_id = $request->service_id;
            $ServiceUser->user_id = Auth::id();
            $ServiceUser->tenant_id = $tenant_id;
            $ServiceUser->save();

            //make the user who creates this tenant the super-admin of this tenant
            Auth::user()->assignRole('super-admin');

            $create_database = env('TENANT_CREATE_DATABASE', 'false');

            if ($create_database === true || $create_database === 'true')
            {
                $database = $this->database($tenant_id);

                $tenant->database = $database;
                $tenant->save();
            }
            else
            {
                $tenant->database = 'rg_accounting'; //set the default db <<>> this value MUST be set
                $tenant->save();
            }

            //setup the default charts of accounts and settings
            $this->tenantSetup($tenant_id);

            DB::commit();

            return [
                'status' => true,
                'messages' => ['Organisation created successful'],
                'callback' => '/settings/organisations'
            ];

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            Log::critical('Error: Failed to save organization to database.');
            Log::critical($e);

            $messages[] = 'System error: Please try again.';

            if (App::environment('local'))
            {
                $messages[] = 'Error: Failed to save organization to database.';
                $messages[] = 'File: ' . $e->getFile();
                $messages[] = 'Line: ' . $e->getLine();
                $messages[] = 'Message: ' . $e->getMessage();
            }

            return [
                'status' => false,
                'messages' => $messages,
                'callback' => '/settings/organisations/create'
            ];
        }

    }

    public function show(Request $request)
    {
    }

    public function edit($id)
    {
        if (!FacadesRequest::wantsJson())
        {
            return view('l-limitless-bs4.layout_2-ltr-default.appVue');
        }

        $Tenant = Tenant::findOrFail($id);
        $attributes = $Tenant->toArray();
        $attributes['_method'] = 'PATCH';

        $data = [
            'pageTitle' => 'Edit Organisation',
            'urlPost' => '/settings/organisations/' . $id, #required
            'attributes' => $attributes,
        ];

        if (FacadesRequest::wantsJson())
        {
            return $data;
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            //'email' => ['required', 'email', 'max:255'],
            'country' => 'required',
            'base_currency' => 'required|string',
        ]);

        if ($validator->fails())
        {
            return ['status' => false, 'messages' => $validator->errors()->all()];
        }

        $tenant = Tenant::find($request->id);

        $tenant->type = $request->type;
        $tenant->name = $request->name;
        $tenant->alias = $request->alias;
        $tenant->email = $request->email;
        $tenant->industry = $request->industry;
        $tenant->country = $request->country;
        $tenant->street_line_1 = $request->street_line_1;
        $tenant->street_line_2 = $request->street_line_2;
        $tenant->city = $request->city;
        $tenant->state_province = $request->state_province;
        $tenant->zip_postal_code = $request->zip_postal_code;
        $tenant->phone = $request->phone;
        $tenant->fax = $request->fax;
        $tenant->website = $request->website;
        $tenant->base_currency = $request->base_currency;
        $tenant->fiscal_year = $request->fiscal_year;
        $tenant->fiscal_year_start = $request->fiscal_year_start;
        $tenant->language = $request->language;
        $tenant->time_zone = $request->time_zone;
        $tenant->date_format = $request->date_format;
        $tenant->company_id_name = $request->company_id_name;
        $tenant->company_id_value = $request->company_id_value;
        $tenant->tax_id_name = $request->tax_id_name;
        $tenant->tax_id_value = $request->tax_id_value;

        if ($request->file('logo'))
        {
            $tenant->logo = Storage::disk('public_storage')->putFile('/', $request->file('logo'));
        }

        if ($tenant->save())
        {
            return [
                'status' => true,
                'messages' => ['Organisation information update successful.'],
                'callback' => '/settings/organisations'
            ];
        }
        else
        {
            return [
                'status' => false,
                'messages' => ['Organisation information update failed.'],
                'callback' => ''
            ];
        }
    }

//	these methods are to be reviewed after shifting to microservice module
//    public function destroy($id)
//	{
//		$tables = [
//			//'rg_accounting_accounts',
//			'rg_accounting_account_balances',
//			'rg_accounting_contact_balances',
//			'rg_accounting_inventory_issues',
//			'rg_accounting_inventory_purchases',
//			'rg_accounting_payment_modes',
//			'rg_accounting_taxes',
//			'rg_accounting_txn_entrees',
//			'rg_accounting_txn_entree_configs',
//			'rg_documents',
//			'rg_document_settings',
//
//			'rg_banking_accounts',
//			'rg_banking_banks',
//			'rg_banking_statements',
//			'rg_banking_transactions',
//			'rg_banking_transaction_import_logs',
//			'rg_banking_transaction_rules',
//
//			'rg_contact_address_book',
//			'rg_contact_bank_accounts',
//			'rg_contact_comments',
//			'rg_contact_contacts',
//			'rg_contact_contact_persons',
//			'rg_contact_users',
//			'rg_contact_withdraw_requests',
//
//			'rg_item_items',
//		];
//
//		foreach ($tables as $table) {
//			DB::table($table)->where('tenant_id', Auth::user()->tenant->id)->delete();
//		}
//	}
//
    public function deleteTxns($id)
    {
        /*
        return [
            'status' => true,
            'message' => 'Txn delete request has been received.'
        ];
        //*/

        if (!App::environment('local'))
        {
            return [
                'status' => false,
                'message' => 'Access violation: This action is not permitted.'
            ];
        }

        AccountBalance::query()->forceDelete();
        ContactBalance::query()->forceDelete();

        Transaction::query()->forceDelete();

        CashSale::query()->forceDelete();
        CashSaleItem::query()->forceDelete();
        CashSaleItemTax::query()->forceDelete();
        CashSaleLedger::query()->forceDelete();

        Bill::query()->forceDelete();
        BillItem::query()->forceDelete();
        BillItemTax::query()->forceDelete();
        BillLedger::query()->forceDelete();

        RecurringBill::query()->forceDelete();
        RecurringBillItem::query()->forceDelete();
        RecurringBillItemTax::query()->forceDelete();

        CreditNote::query()->forceDelete();
        CreditNoteItem::query()->forceDelete();
        CreditNoteItemTax::query()->forceDelete();
        CreditNoteLedger::query()->forceDelete();

        DebitNote::query()->forceDelete();
        DebitNoteItem::query()->forceDelete();
        DebitNoteItemTax::query()->forceDelete();
        DebitNoteLedger::query()->forceDelete();

        Estimate::query()->forceDelete();
        EstimateItem::query()->forceDelete();
        EstimateItemTax::query()->forceDelete();

        Expense::query()->forceDelete();
        ExpenseItem::query()->forceDelete();
        ExpenseItemTax::query()->forceDelete();
        ExpenseLedger::query()->forceDelete();

        RecurringExpense::query()->forceDelete();
        RecurringExpenseItem::query()->forceDelete();
        RecurringExpenseItemTax::query()->forceDelete();

        GoodsDelivered::query()->forceDelete();
        GoodsDeliveredItem::query()->forceDelete();
        GoodsDeliveredLedger::query()->forceDelete();

        GoodsIssued::query()->forceDelete();
        GoodsIssuedItem::query()->forceDelete();
        GoodsIssuedLedger::query()->forceDelete();

        GoodsReceived::query()->forceDelete();
        GoodsReceivedItem::query()->forceDelete();
        GoodsReceivedLedger::query()->forceDelete();

        GoodsReturned::query()->forceDelete();
        GoodsReturnedItem::query()->forceDelete();
        GoodsReturnedLedger::query()->forceDelete();

        Invoice::query()->forceDelete();
        InvoiceItem::query()->forceDelete();
        InvoiceItemTax::query()->forceDelete();
        InvoiceLedger::query()->forceDelete();

        RecurringInvoice::query()->forceDelete();
        RecurringInvoiceItem::query()->forceDelete();
        RecurringInvoiceItemTax::query()->forceDelete();

        PaymentMade::query()->forceDelete();
        PaymentMadeItem::query()->forceDelete();
        PaymentMadeItemTax::query()->forceDelete();
        PaymentMadeLedger::query()->forceDelete();

        PurchaseOrder::query()->forceDelete();
        PurchaseOrderItem::query()->forceDelete();
        PurchaseOrderItemTax::query()->forceDelete();

        PaymentReceived::query()->forceDelete();
        PaymentReceivedItem::query()->forceDelete();
        PaymentReceivedItemTax::query()->forceDelete();
        PaymentReceivedLedger::query()->forceDelete();

        RetainerInvoice::query()->forceDelete();
        RetainerInvoiceItem::query()->forceDelete();
        RetainerInvoiceItemTax::query()->forceDelete();
        RetainerInvoiceLedger::query()->forceDelete();

        SalesOrder::query()->forceDelete();
        SalesOrderItem::query()->forceDelete();
        SalesOrderItemTax::query()->forceDelete();

        return [
            'status' => true,
            'message' => 'Organization transactions deleted.'
        ];
    }

    public function switch($id)
    {
        $tenant = Tenant::findOrFail($id);

        session(['tenant_id' => $tenant->id]);

        return redirect()->route('accounting.index');
    }

    public function test()
    {
        $db = 'rg_tenant_test001';
        $db_username = config('database.connections.system.username');

        $privileges = DB::select("SELECT Create_priv FROM mysql.user WHERE USER = '" . $db_username . "' ");

        if ($privileges[0]->Create_priv == 'Y')
        {
            //return 'user has permission';
        }
        else
        {
            return 'user does not have permission';
        }


        DB::statement('DROP DATABASE IF EXISTS `' . $db . '`');
        DB::statement('CREATE DATABASE IF NOT EXISTS `' . $db . '`');

        //$db = 'rg_tenant_'.time();
        //DB::statement('CREATE DATABASE `'.$db.'`');

        config(['database.connections.tenant.database' => $db]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        //return config('database.connections.tenant.database');

        config(['database.default' => 'tenant']); //change default connection to tenant

        #todo #rutatiina update the migration bellow to the current ones
        //Artisan::call('migrate', array('--path' => 'packages/rutatiina/banking/database/migrations'));
        //Artisan::call('migrate', array('--path' => 'packages/rutatiina/contact/database/migrations'));
        //Artisan::call('migrate', array('--path' => 'packages/rutatiina/item/database/migrations'));

        return $db; //DB::getDatabaseName();
    }

    public function fixSettings()
    {
        $tenants = Tenant::where('id', '>', 51)->get();

        $i = 0;
        foreach ($tenants as $tenant)
        {
            if (Account::withoutGlobalScopes()->where('tenant_id', $tenant->id)->first()) continue;

            $this->tenantSetup($tenant->id);
            $i++;
        }

        return 'Settings for tenant accounts (' . $i . ') over 51, complete';
    }

}
