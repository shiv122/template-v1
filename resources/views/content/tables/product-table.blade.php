@extends('layouts/contentLayoutMaster')

@section('title', 'Users')

@section('vendor-style')

@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">


@endsection


@section('content')

    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-bodyd">
                        <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <livewire:product-table />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-side-modal title="Update document-type" id="edit-product">
        <x-form id="edit-product" :route="route('admin.product.update')">

            <div class="col-12">
                <x-input type="text" name="name" />
            </div>
            <div class="col-12">
                <x-input type="number" name="price" />
            </div>


        </x-form>
    </x-side-modal>
@endsection

@section('vendor-script')

@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            window.Livewire.on('edit', data => {
                console.log(data);
                const id = data.id;
                const route = data.route;
                const offcanvas = new bootstrap.Offcanvas(document.querySelector(data.offcanvas));

                rebound({
                    route: route,
                    method: 'GET',
                    data: {
                        id: id,
                    },
                    successCallback: function(response) {
                        console.log(response);
                        $('#edit-product').find('input[name="name"]').val(response.name);
                        $('#edit-product').find('input[name="price"]').val(response.price);
                        $('#edit-product').find('input[name="id"]').val(response.id);
                        offcanvas.show();

                    }
                })
            })
        });
    </script>
@endsection
