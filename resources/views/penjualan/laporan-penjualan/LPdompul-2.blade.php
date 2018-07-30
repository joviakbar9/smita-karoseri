@extends('adminlte::page')

@section('title', 'Laporan Dompul')

@section('content_header')
    <h1>Laporan Penjualan Dompul</h1>

@stop

@section('css')

@stop

@section('content')

<div class="cotainer-fluid">
  <div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        Tanggal Awal
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        : 121212
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        Tanggal Akhir
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        : 232312
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        Nama Kasir
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        : Joni
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        Nama Logistic
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        : gege
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        Nama PIC
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        : jhon
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        ID Canvaser
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        : 123123123
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        Nama Canvaser
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        : name
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">

    </div>
  </div>
</div>


<table id="lp-dompul-2-table" class="table responsive" width="100%">
    <thead>
    <tr>
        <th>No.</th>
        <th>Nama RO</th>
        <th>Total Tagihan</th>
        <th>Piutang</th>
    </tr>
    </thead>
    <tfoot>
      <tr>
        <td></td>
        <td><b>Grand Total</b></td>
        <td>total semua tagihan</td>
        <td>total piutang</td>
      </tr>
    </tfoot>
</table>

@stop

@section('js')
<script>
    $(function () {
        $('#lp-dompul-table').DataTable({
            serverSide: true,
            processing: true,
            ajax: '/lp-dompul-data',
            columns: [
                {data: 'id_lokasi'},
                {data: 'nm_lokasi'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@stop
