<?php

namespace App\Http\Controllers;
use App\UploadDompul;
use App\Dompul;
use App\Sales;
use App\Customer;
use App\HargaDompul;
use DB;
use Excel;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UploadDompulController extends Controller {
    public
    function index() {
        return view('upload.upload');
    }
    public
    function downloadExcel($type) {
        $data = UploadDompul::get()->toArray();
        return Excel::create('dompul', function ($excel) use($data) {
            $excel->sheet('mySheet', function ($sheet) use($data) {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
    public
    function importExcel(Request $request) {
        $faker = Faker::create();
        // Get data from database
        $db_sub_master = Dompul::select('nama_sub_master_dompul')->get();
        $db_downline = Customer::select('nm_cust')->get();
        $db_kanvacer = Sales::select('nm_sales')->get();
        $db_faktur = UploadDompul::select('no_faktur')->get();
        //Create Array
        $sub_master = [];
        $downline = [];
        $kanvacer = [];
        $faktur = [];
        //Add data to array
        foreach ($db_sub_master as $key => $value) {
            $sub_master[]=$value->nama_sub_master_dompul;
        }
        foreach ($db_downline as $key => $value) {
            $downline[]=$value->nm_cust;
        }
        foreach ($db_kanvacer as $key => $value) {
            $kanvacer[]=$value->nm_sales;
        }
        foreach ($db_faktur as $key => $value) {
            $faktur[]=$value->no_faktur;
        }

        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path, function ($reader) {
                $reader->ignoreEmpty();
            })->get();
            if (!empty($data) && $data->count()) {
                foreach($data as $key => $value) {
                    if (!empty($value) && $value->count()) {
                        if (!empty($value->hp_sub_master)) {
                            if (in_array($value->no_faktur, $faktur)) {
                                // continue;
                            }else{
                                $uploadDompul[] = ['no_hp_sub_master_dompul' => $value->hp_sub_master,
                                'nama_sub_master_dompul' => $value->nama_sub_master,
                                'tanggal_transfer' => $value->tanggal_trx,
                                'tanggal_upload' => Carbon::now('Asia/Jakarta')->toDateString(),
                                'inbox' => ($value->inbox==null) ? 0 : $value->inbox ,
                                'no_faktur' => $value->no_faktur,
                                'produk' => $value->produk,
                                'qty' => str_replace(',', '', $value->qty),
                                'balance' => str_replace(',', '', $value->balance),
                                'diskon' => $value->diskon,
                                'no_hp_downline' => $value->hp_downline,
                                'nama_downline' => $value->nama_downline,
                                'status' => $value->status,
                                'no_hp_canvasser' => $value->hp_kanvacer,
                                'nama_canvasser' => $value->nama_kanvacer,
                                'harga_dompul' => HargaDompul::where('nama_harga_dompul',$value->produk)
                                                    ->where('tipe_harga_dompul','CVS')
                                                    ->first()
                                                    ->harga_dompul,
                                'print' => $value->print,
                                'bayar' => $value->bayar,
                                'qty_program' => 0
                                // 'tipe_dompul' => ''
                            ];
                            // $faktur[] = $value->no_faktur;
                            }

                            if (in_array($value->nama_sub_master, $sub_master)) {
                                // continue;
                            }else{
                                $dompul[] = ['no_hp_master_dompul' => $faker->phoneNumber,
                                'no_hp_sub_master_dompul' => $value->hp_sub_master,
                                'nama_sub_master_dompul' => $value->nama_sub_master,
                                'tipe_dompul' => substr($value->nama_sub_master, 0, 3),
                                'id_gudang' => 0,
                                'status_sub_master_dompul' => 'Aktif'
                            ];   
                                $sub_master[] = $value->nama_sub_master;
                            }
                            // $hargaDompul[] = ['nama_harga_dompul' => $value->produk ,
                            //     'harga_dompul' => $value->harga_dompul ,
                            //     'tanggal_update' => $value->tanggal_update_dompul ,
                            //     'tipe_harga_dompul' => $value->tipe_harga_dompul,
                            //     'status_harga_dompul' => 'Aktif'
                            // ];

                            if (in_array($value->nama_downline, $downline)) {
                                // continue;
                            }else{
                                $customer[] = ['nm_cust' => $value->nama_downline,
                                'alamat_cust' => $faker->address,
                                'no_hp' => $value->hp_downline,
                                'jabatan' => $faker->jobTitle
                            ];
                                $downline[] = $value->nama_downline;
                            }

                            if (in_array($value->nama_kanvacer, $kanvacer)) {
                                // continue;
                            }else{
                                $sales[] = ['nm_sales' => $value->nama_kanvacer,
                                'alamat_sales' => $faker->address,
                                'id_lokasi' => 0,
                                'no_hp' => $value->hp_kanvacer
                            ];   
                                $kanvacer[] = $value->nama_kanvacer;
                            }
                        }
                    }
                }
                try {
                if (!empty($uploadDompul)) {
                    DB::table('upload_dompuls')->insert($uploadDompul);
                }
                if(!empty($dompul)){
                    DB::table('master_dompuls')->insert($dompul);
                }
                // if(!empty($hargaDompul)){
                //     DB::table('master_harga_dompuls')->insert($hargaDompul);
                // }
                if(!empty($customer)){
                    DB::table('master_customers')->insert($customer);
                }
                if(!empty($sales)){
                    DB::table('master_saless')->insert($sales);
                }
                } catch (Exception $e) {

                }
                
            }
        }
        return redirect('/upload/dompul');

    }
    /**
     * Drop row on Upload Dompul Table
     *
     */
    public function empty() {
        UploadDompul::truncate();
        return redirect()->back();
    }

    public function aktifasi($tgl_transfer, $tgl_upload){
        uploadDompul::where('tanggal_transfer',$tgl_transfer)
            ->where('tanggal_upload',$tgl_upload)
            ->update(['status_active' => 1]);
        return redirect()->back();
    }

    /**
     * Process dataTable ajax response.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadData(Datatables $datatables) {
        return $datatables->eloquent(UploadDompul::select(DB::raw('tanggal_transfer,tanggal_upload, IF(status_active=1, "Aktif", "Tidak Aktif") as status_active, COUNT(no_faktur) as jumlah_transaksi'))
        ->groupBy('tanggal_transfer','tanggal_upload','status_active'))
       ->addColumn('action', function ($uploadDompul) {
                return '<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#detailModal" data-transfer="'.$uploadDompul->tanggal_transfer.'" data-upload="'.$uploadDompul->tanggal_upload.'"><i class="glyphicon glyphicon-edit"></i> Lihat Data</a>
                <a class = "btn btn-xs btn-warning" data-toggle="modal" data-target="#activationModal" data-transfer="'.$uploadDompul->tanggal_transfer.'" data-upload="'.$uploadDompul->tanggal_upload.'"><i class="glyphicon glyphicon-remove"></i> Aktifasi</a>';
            })->make(true);
    }
    /**
     * Process dataTable ajax response.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatables, $transfer, $upload) {
        return $datatables->eloquent(UploadDompul::where('tanggal_transfer',$transfer)
                                                ->where('tanggal_upload',$upload)) 
       ->addColumn('action', function ($uploadDompul) {
                return '<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editModal"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a class = "btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="'.$uploadDompul->id_upload.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
            })->make(true);
    }

}