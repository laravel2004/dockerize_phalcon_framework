{% extends 'layouts/main.volt' %}

{% block content %}
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row gap-4 justify-content-md-between align-items-start align-items-md-center mb-3">
                <form method="GET" action="/frontend/material" class="d-flex flex-column flex-sm-row mb-3 w-100 mb-md-0">
                    <input type="text" name="search" class="form-control w-100 me-0 me-sm-2 mb-2 mb-sm-0" placeholder="Search by name" value="{{ search }}">
                    <button type="submit" class="btn btn-secondary w-sm-auto">Search</button>
                </form>
                <button type="button" class="btn btn-primary w-30 w-md-auto" id="btnAddModal" data-bs-toggle="modal" data-bs-target="#addUomModal">Add</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>UoM</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% if page.items|length == 0 %}
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                        {% else %}
                        {% for material in page.items %}
                        <tr>
                            <td>{{ loop.index + ((page.current - 1) * page.limit) }}</td>
                            <td>{{ material.name }}</td>
                            <td>{{ material.stock }}</td>
                            <td class="format-rupiah">{{ material.price }}</td>
                            <td>{{ material.uom }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ material.id }}"
                                    data-name="{{ material.name }}"
                                    data-uom="{{ material.conversion_uom_id }}"
                                    data-price="{{ material.price }}"
                                    data-stock="{{ material.stock }}">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ material.id }}">Delete</button>
                            </td>
                        </tr>
                        {% endfor %}
                        {% endif %}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Adding/Editing -->
<div class="modal fade" id="addUomModal" tabindex="-1" aria-labelledby="addUomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUomModalLabel">Add Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEditUomForm">
                    <input name="id" type="hidden" id="uomId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" class="form-control" id="materialName" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">UoM</label>
                        <select class="form-select" id="materialUom" name="conversion_uom_id" required>
                            <option value="">Select UoM</option>
                            {% for uom in conversionUoms %}
                            <option value="{{ uom.id }}">{{ uom.name }}</option>
                            {% endfor %}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input placeholder="Enter Price" name="price" type="number" class="form-control" id="materialPrice" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input placeholder="Enter stock" name="stock" type="number" class="form-control" id="materialStock" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
    }

    $('.format-rupiah').each(function() {
        $(this).text(formatRupiah($(this).text()));
    });

    $('#btnAddModal').click(function() {
        $('#addEditUomForm')[0].reset();
        $('#uomId').val('');
        $('#addUomModalLabel').text('Add Material');
    });

    $('.edit-btn').click(function() {
        $('#uomId').val($(this).data('id'));
        $('#materialName').val($(this).data('name'));
        $('#materialUom').val($(this).data('uom'));
        $('#materialPrice').val($(this).data('price'));
        $('#materialStock').val($(this).data('stock'));
        $('#addUomModalLabel').text('Edit Material');
        $('#addUomModal').modal('show');
    });

    $('#addEditUomForm').submit(function(event) {
        event.preventDefault();
        let formData = $(this).serialize();
        let id = $('#uomId').val();
        let url = id ? `/frontend/material/update/${id}` : '/frontend/material/save';
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(response) {
                Swal.fire('Success', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message, 'error');
            }
        });
    });

    $('.delete-btn').click(function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/frontend/material/delete/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            }
        });
    });
});
</script>
{% endblock %}
