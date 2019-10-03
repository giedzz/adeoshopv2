@extends('layouts.app')
@section('content')
<div class="container">
    <div>
        <div class="float-left">
            <div class="form-inline">
                <label class="mr-1">Include tax?</label>
                <input type="checkbox" id="tax_inclusion" class="checkbox" />
                <label for="tax_inclusion" class="switch"></label>
                <div class="form-inline">
                    <label class="ml-1">Tax rate %: </label>
                    <input class="form-control ml-1 w-50" type="number" name="tax_rate" id="tax_rate" min="0.00" max="100.00" step="0.01" />
                </div>

            </div>
        </div>
        <div class="float-right">
            <a href="#" class="btn btn-danger btn-sm mb-2" onclick="bulkDelete()">Bulk Delete</a>
            <button type="button" name="add_data" id="add_data" class="btn btn-success btn-sm mb-2">
                Add item
            </button>
        </div>
        <div class="float-left">
            <div class="form-inline">
                <label class="mr-1">Discount %?</label>
                <input type="checkbox" id="discount_type" class="checkbox" />
                <label for="discount_type" class="switch"></label>
                <input class="form-control ml-1" type="number" name="discount_rate" id="discount_rate" min="0.00" max="100.00" step="0.01" />
                <label class="mr-1" id="discount_error"></label>
            </div>
        </div>

    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">Name</th>
                <th scope="col">SKU</th>
                <th scope="col">Status</th>
                <th scope="col">Base price</th>
                <th scope="col">Special price</th>
                <th scope="col">Image</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="product_table">

        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        // tinymce.init({
        //     selector: '#description',
        // });
        $.ajax({
            type: "get",
            url: '{{ route("home.getConfigData") }}',
            data: {},
            dataType: 'json',
            success: function(data) {
                $('#tax_rate').val(data.tax_rate);
                if (data.tax_inclusion === 1) {
                    $("#tax_inclusion").prop("checked", true);
                } else if (data.tax_inclusion === 0) {
                    $("#tax_inclusion").prop("checked", false);
                }
                if (data.discount_type === 1) {
                    $("#discount_type").prop("checked", true);
                    $("#discount_rate").val(data.discount_percent);
                } else if (data.discount_type === 0) {
                    $("#discount_type").prop("checked", false);
                    $("#discount_rate").val(data.discount_fixed);
                }
            }
        })
        getData();
        $('#tax_inclusion').change(function() {
            $.ajax({
                type: "POST",
                url: '{{ route("home.taxInclusion") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    checkboxstatus: $('#tax_inclusion').prop('checked')
                },

            })
        });
        $('#discount_type').change(function() {
            $.ajax({
                type: "POST",
                url: '{{ route("home.discountTypeChange") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    checkboxstatus: $('#discount_type').prop('checked')
                },
                success: function(data) {
                    $('#discount_rate').val(data);
                }
            })
        });
        $('#tax_rate').change(function() {
            $.ajax({
                type: "POST",
                url: '{{ route("home.taxRate") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    tax_rate: $('#tax_rate').val()
                },
            })
        })
        $('#discount_rate').change(function() {
            if ($('#discount_type').prop('checked') == true) {
                if ($('#discount_rate').val() <= 100) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route("home.discountRate") }}',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            discount_type: $('#discount_type').prop('checked'),
                            discount_rate: $('#discount_rate').val()
                        }
                    })
                }else{
                    $('#discount_error').html('<div class="alert alert-danger m-1 p-1">Value over 100</div>')
                }
            } else {
                $.ajax({
                    type: "POST",
                    url: '{{ route("home.discountRate") }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        discount_type: $('#discount_type').prop('checked'),
                        discount_rate: $('#discount_rate').val()
                    }
                })
            }
        })

        $('#add_data').click(function() {
            $('#productModal').modal('show');
            $('#product_form')[0].reset();
            $('#description').summernote("code", "");
            $('#form_output').html('');
            $('#button_action').val('insert');
            $('#modal-title').text('Add Data');
            $('#file-form').show();
            $('#action').val('Add');

        });
        $('#product_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route("home.postdata") }}',
                method: "POST",
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.error.length > 0) {
                        var error_html = '';
                        for (var count = 0; count < data.error.length; count++) {
                            error_html += '<div class="alert alert-danger">' + data.error[count] + '</div>';
                        }
                        $('#form_output').html(error_html);
                    } else {
                        $('#form_output').html(data.success);
                        $('#product_form')[0].reset();
                        $('#action').val('Add');
                        $('#button_action').val('insert');
                        $('#description').summernote("code", "");
                        getData();
                    }
                }
            })
        })

    });

    function getData() {
        $.get('{{ route("home.getdata") }}', function(data) {
            $('#product_table').empty().html(data);
        });
    };

    function bulkDelete() {
        var id = [];
        if (confirm("Are you sure you want to Delete this data?")) {
            $('.product_checkbox:checked').each(function() {
                id.push($(this).val());
            });
            if (id.length > 0) {
                $.ajax({
                    url: "{{ route('home.massRemove')}}",
                    method: "get",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        alert(data);
                        getData();
                    }
                });
            } else {
                alert("Please select atleast one item");
            }
        }
    }

    function checkTheCheckbox(id) {
        if (document.getElementById(id).checked == true) {
            document.getElementById(id).checked = false;
            let papa = document.getElementById(id).parentElement.parentElement;
            papa.classList.remove("highlighted")
        } else {
            document.getElementById(id).checked = true;
            let papa = document.getElementById(id).parentElement.parentElement;
            papa.classList.add("highlighted");
        }
    }

    function deleteItem(id) {
        if (confirm("Are you sure you want to delete this item?")) {
            $.ajax({
                url: "{{route('home.removeData')}}",
                method: "get",
                data: {
                    id: id
                },
                success: function(data) {
                    alert(data);
                    getData();
                }
            })
        } else {
            return false;
        }
    }

    function editItem(id) {
        $('#form_output').html('');
        $.ajax({
            url: "{{route('home.fetchData')}}",
            method: "get",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                $('#name').val(data.name);
                $('#sku').val(data.sku);
                $('#status').val(data.status);
                $('#base_price').val(data.base_price);
                $('#special_price').val(data.special_price);
                $('#file-form').hide();
                $('#description').summernote("code", data.description);
                $('#id').val(data.id);
                $('#productModal').modal('show');
                $('#action').val('Edit');
                $('.modal-title').text('Edit Data');
                $('#button_action').val('update');
            }
        })
    }


    function getConfigData() {
        $.ajax({
            type: "get",
            url: '{{ route("home.getConfigData") }}',
            data: {
                "_token": "{{ csrf_token() }}",
                checkboxstatus: checkboxstatus
            },
            success: function(data){
                console.log(data);
            }
        })
    }
</script>
<div id="productModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="product_form">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Add Data</h4>
                    <button class="close" type="button" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Item name</label>
                        <input type="text" name="name" id="name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" id="sku" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Enable item</label>
                        <select class="custom-select" id="status" name="status">
                            <option selected>Show item for the consumer</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Base price</label>
                        <input class="form-control" type="number" name="base_price" id="base_price" min="0.00" max="10000.00" step="0.01" />
                    </div>
                    <div class="form-group">
                        <label>Special price</label>
                        <input class="form-control" type="number" name="special_price" id="special_price" min="0.00" max="10000.00" step="0.01" />
                    </div>
                    <div class="input-group" id="file-form">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="select_file" name="select_file">
                            <label class="custom-file-label">Choose file</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" value="Add" name="submit" id="action" class="btn btn-default" />
                </div>
            </form>
        </div>
    </div>
</div>

@endsection