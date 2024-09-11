@section('body')
    @if (Session::has('message'))
        <div class="alert {{ Session::get('alert') }} fade in">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <strong>{{ Session::get('message') }}</strong>
        </div>
    @endif 

    <div class="dr"><span></span></div> 

    {!! Form::open(['url' => 'laporans', 'files' => true, 'target' => '_blank']) !!}
        <div class="row-fluid">
            <div class="head clearfix">
                <div class="isw-zoom"></div>
                <h1>Laporan Tahunan {{$judul}}</h1>  
            </div>

            <div class="row-form clearfix" style="background:#fff">
                <div class="span1" style="text-align:right"><b>Nama Pos</b></div>
                <div class="span3">
                    <select name="pos_id" required='required' id='s2_1' style="width:100%">
                        <!-- @foreach($pos as $po)
                            @if($po->model == 0)
                                <option value="{{$po->id}}">{{$po->kode_database}} - {{$po->nama_stasiun}}</option>
                            @else
                                <option value="{{$po->id}}">{{$po->kode_stasiun}} - {{$po->nama_stasiun}}</option>
                            @endif
                        @endforeach -->
                    </select>
                    {!! Form::hidden('model', $model, ['id' => 'model']) !!}
                </div>

                <div class="span1" style="text-align:right"><b>Tahun</b></div>
                <div class="span2">
                    {!! Form::text('tahun1', $tahun, ['class' => 'tahun', 'id' => 'tahun1']) !!}
                </div>

                <div class="span2">
                    {!! Form::text('tahun2', $tahun, ['class' => 'tahun', 'id' => 'tahun2']) !!}
                </div>

                <div class="span3">
                    <button class="btn btn-danger">Tampilkan Report</button> 
                    <a class="btn btn-success" onclick="excel_data()">Excel</a>
                </div> 
            </div>
        </div>
    {!! Form::close() !!}
@endsection

@section('java')
    <script>
        $('.tahun').Zebra_DatePicker({
            format: 'Y',
            view: 'years',
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'],
            days_abbr: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu']
        });

        function excel_data() {
            var pos = $('#s2_1').val();
            var model = $('#model').val();
            var tahun1 = $('#tahun1').val();
            var tahun2 = $('#tahun2').val();

            window.location.href = "excel_data/" + pos + "_" + model + "_" + tahun1 + "_" + tahun2;    
        } 
    </script>
@endsection
