<?php

namespace App\Http\Controllers;
use App\UploadDompul;
use DB;
use Excel;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class UploadDompulController extends Controller
{
        public function index()
	{
		return view('upload.upload');
	}
	public function downloadExcel($type)
	{
		$data = UploadDompul::get()->toArray();
		return Excel::create('dompul', function($excel) use ($data) {
			$excel->sheet('mySheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}
	public function importExcel(Request $request)
	{
		if($request->hasFile('import_file')){
            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
			},'UTF-8')->get();
			if(!empty($data) && $data->count()){
				foreach ($data as $key => $value) {
                    $uploadDompul[] = ['no_hp_sub_master_dompul' => $value->hp_sub_master ,
                        'nama_sub_master_dompul' => $value->nama_sub_master ,
                        'tanggal_transfer' => $value->tanggal_trx ,
                        'no_faktur' => $value->no_faktur ,
                        'produk' => $value->produk ,
                        'qty' => str_replace(',','',$value->qty),
                        'balance' => str_replace(',','',$value->balance),
                        'diskon' => $value->diskon ,
                        'no_hp_downline' => $value->hp_downline ,
                        'nama_downline' => $value->nama_downline ,
                        'status' => $value->status ,
                        'no_hp_canvasser' => $value->hp_kanvacer ,
                        'nama_canvasser' => $value->nama_kanvacer ,
                        'print' => $value->print,
                        'bayar' => $value->bayar 
                    ];
                    // $uploadDompul[] = ['no_hp_sub_master_dompul' => $value->hp_sub_master ,
                    //     'nama_sub_master_dompul' => $value->nama_sub_master ,
                    //     'tanggal_transfer' => $value->tanggal_trx ,
                    //     'no_faktur' => $value->no_faktur ,
                    //     'produk' => $value->produk ,
                    //     'qty' => $value->qty ,
                    //     'balance' => $value->balance ,
                    //     'diskon' => $value->diskon ,
                    //     'no_hp_downline' => $value->hp_downline ,
                    //     'nama_downline' => $value->nama_downline ,
                    //     'status' => $value->status ,
                    //     'no_hp_canvasser' => $value->hp_kanvacer ,
                    //     'nama_canvasser' => $value->nama_kanvacer ,
                    //     'print' => $value->print,
                    //     'bayar' => $value->bayar 
                    // ];
				}
				if(!empty($uploadDompul)){
					DB::table('upload_dompuls')->insert($uploadDompul);
				}
			}
		}
		return redirect('/upload/dompul');

    }
    /**
     * Process dataTable ajax response.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Datatables $datatables)
    {
        return $datatables->eloquent(UploadDompul::query())
                          ->addColumn('action', function ($uploadDompul) {
                              return
                              '<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editModal" data-id="'.$uploadDompul->id_upload.'"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                              <a class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="'.$uploadDompul->id_upload.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
                            })
                          ->make(true);
    }

}