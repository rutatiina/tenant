<?php

namespace Rutatiina\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Rutatiina\Tenant\Models\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Rutatiina\Qbuks\Models\ServiceUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Rutatiina\Tenant\Models\TenantPaymentDetail;

class TenantPaymentDetailsController extends Controller
{
    private $documentOptions = [
        [
            'text' => "Estimate / Quotation",
            'value' => "estimate"
        ],
        [
            'text' => "Sales orders",
            'value' => "sales-order"
        ],
        [
            'text' => "Invoices",
            'value' => "invoice"
        ],
        [
            'text' => "Recurring invoice",
            'value' => "recurring-invoice"
        ],
        [
            'text' => "Credit note",
            'value' => "credit-note"
        ]
    ];

    public function __construct()
    {
        // $this->middleware('tenant', ['only' => ['tenant', 'index', 'edit', 'deleteTxns', 'test']]);

        // $this->middleware('permission:tenants.view')->except('tenant');
        // $this->middleware('permission:tenants.create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:tenants.update', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:tenants.delete', ['only' => ['destroy', 'deleteTxns']]);
    }

    public function index(Request $request)
    {
        $per_page = ($request->per_page) ? $request->per_page : 20;

        if (!FacadesRequest::wantsJson())
        {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        return [
            'tableData' => TenantPaymentDetail::paginate($per_page)
        ];
    }

    public function create()
    {
        if (!FacadesRequest::wantsJson())
        {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $Tenant = new Tenant;
        $attributes = $Tenant->rgGetAttributes();
        $attributes['_method'] = 'POST';

        //set the default attribute values
        $attributes['service_id'] = 1;
        $attributes['country'] = 'UG';
        $attributes['base_currency'] = 'UGX';
        $attributes['language'] = 'en';
        $attributes['time_zone'] = 'Africa/Kampala';
        $attributes['date_format'] = 'yyyy-dd-mm';

        $data = [
            'pageTitle' => 'Add Organisation payment details',
            'urlPost' => '/settings/organisations/payment-details', #required
            'attributes' => $attributes,
            'documentOptions' => $this->documentOptions
        ];

        if (FacadesRequest::wantsJson())
        {
            return $data;
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document' => ['required', 'string', 'max:100'],
            'details' => ['required', 'string'],
        ]);

        if ($validator->fails())
        {
            return ['status' => false, 'messages' => $validator->errors()->all()];
        }

        $tenantPaymentDetail = new TenantPaymentDetail();

        $tenantPaymentDetail->tenant_id = Auth::user()->tenant->id;
        $tenantPaymentDetail->document = $request->document;
        $tenantPaymentDetail->details = $request->details;
        $tenantPaymentDetail->save();

    
        return [
            'status' => true,
            'messages' => ['Organisation created successful'],
            'callback' => '/settings/organisations/payment-details'
        ];


    }

    public function show(Request $request)
    {
    }

    public function edit($id)
    {
        if (!FacadesRequest::wantsJson())
        {
            return view('ui.limitless::layout_2-ltr-default.appVue');
        }

        $Tenant = TenantPaymentDetail::findOrFail($id);
        $attributes = $Tenant->toArray();
        $attributes['_method'] = 'PATCH';

        $data = [
            'pageTitle' => 'Edit Organisation payment details',
            'urlPost' => '/settings/organisations/payment-details/' . $id, #required
            'attributes' => $attributes,
            'documentOptions' => $this->documentOptions
        ];

        if (FacadesRequest::wantsJson())
        {
            return $data;
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document' => ['required', 'string', 'max:100'],
            'details' => ['required', 'string'],
        ]);

        if ($validator->fails())
        {
            return ['status' => false, 'messages' => $validator->errors()->all()];
        }

        $tenantPaymentDetail = TenantPaymentDetail::find($request->id);

        $tenantPaymentDetail->document = $request->document;
        $tenantPaymentDetail->details = $request->details;
        $tenantPaymentDetail->save();

        if ($tenantPaymentDetail->save())
        {
            return [
                'status' => true,
                'messages' => ['Organisation payment details update successful.'],
                'callback' => '/settings/organisations/payment-details'
            ];
        }
        else
        {
            return [
                'status' => false,
                'messages' => ['Organisation payment details update failed.'],
                'callback' => ''
            ];
        }
    }
    
    public function destroy($id)
    {
        $record = TenantPaymentDetail::find($id);

        if ($record && $record->delete())
        {
            return [
                'status' => true,
                'messages' => ['Payment details deleted'],
                //'callback' => route('payments-made.index', [], false)
            ];
        }
        else
        {
            return [
                'status' => false,
                'messages' => ['Error: Failed to delete payment details. Please try again.'],
            ];
        }
    }

}
