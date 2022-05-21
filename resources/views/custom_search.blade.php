<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DataTables - Individual Column Search in Datatables using Ajax</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <style>
        td.details-control {
            background: url('../resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('../resources/details_close.png') no-repeat center center;
        }
    </style>
 </head>
 <body>
  <div class="container">    
     <br />
     <h3 align="center">DataTables - Custom Search in Datatables using Ajax</h3>
     <br />
            <br />
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="filter_gender" id="filter_gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="filter_country" id="filter_country" class="form-control" required>
                            <option value="">Select Country</option>
                            @foreach($country_name as $country)

                            <option value="{{ $country->Country }}">{{ $country->Country }}</option>

                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" align="center">
                        <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>

                        <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
            <br />
   <div class="table-responsive">
    <table id="customer_data" style="font-size: 13px; width: 100%" class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Customer Name</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Postal Code</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                </table>
   </div>
            <br />
            <br />
  </div>
 </body>
</html>

<script>
$(document).ready(function(){

    fill_datatable();

    function fill_datatable(filter_gender = '', filter_country = '')
    {
        function format(d) {
            return (
                //'<tr role="row" class="odd">'+
                    '<td class="details-control sorting_1"></td>'+
                    '<td>Maria Anders</td><td>Female</td><td>Obere Str. 57</td>'+
                    '<td>Berlin</td><td>12209</td><td>Germany</td>'
                //'</tr>'
            );
        }

        var dataTable = $('#customer_data').DataTable({
            paging: false,  
            processing: true,
            serverSide: true,
            scrollY:"350px",
            scrollCollapse: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            ajax:{
                url: "{{ route('customsearch.index') }}",
                data:{filter_gender:filter_gender, filter_country:filter_country}
            },
            columns: [
                {
                class: 'details-control',
                orderable: false,
                data: null,
                defaultContent: '',
                },
                {
                    data:'CustomerName',
                    name:'CustomerName'
                },
                {
                    data:'Gender',
                    name:'Gender'
                },
                {
                    data:'Address',
                    name:'Address'
                },
                {
                    data:'City',
                    name:'City'
                },
                {
                    data:'PostalCode',
                    name:'PostalCode'
                },
                {
                    data:'Country',
                    name:'Country'
                }
            ]
        });


        // Array to track the ids of the details displayed rows
        var detailRows = [];
        
        $('#customer_data tbody').on('click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dataTable.row(tr);
            var idx = detailRows.indexOf(tr.attr('id'));

            if (row.child.isShown()) {
                tr.removeClass('details');
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details');
                row.child(format(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });

        // On each draw, loop over the `detailRows` array and show any child rows
        dataTable.on('draw', function () {
            detailRows.forEach(function(id, i) {
                $('#' + id + ' td.details-control').trigger('click');
            });
        });

    }//End of DataTables

});
</script>
