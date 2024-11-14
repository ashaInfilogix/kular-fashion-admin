<div class="modal fade" id="addVariantModal" tabindex="-1" aria-labelledby="addVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVariantModalLabel">Add New Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addVariantForm">
                    <div class="mb-3">
                        <label for="supplier_color_code" class="form-label">Supplier Color Code</label>
                        <input type="text" id="supplier_color_code" class="form-control"
                            placeholder="Enter Supplier Color Code" required>
                        <div id="supplierColorCodeError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="color_select" class="form-label">Select Color</label>
                        <select id="color_select" class="form-control" required>
                            <option value="" disabled selected>Select Color</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->color_name }} ({{ $color->color_code }})
                                </option>
                            @endforeach
                        </select>
                        <div id="colorSelectError" class="invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveVariantBtn" class="btn btn-primary">Save Variant</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="copyQuantityModal" tabindex="-1" aria-labelledby="copyQuantityLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="copyQuantityLabel">Duplicate Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="copy_quantity_for_color" class="form-label">Select Color</label>
                    <select id="copy_quantity_for_color" class="form-control" required>
                        <option value="" disabled selected>Select Color</option>
                        @foreach ($savedColors as $color)
                            <option value="{{ $color['id'] }}">{{ $color['color_name'] }} ({{ $color['color_code'] }})
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select color for which you want to copy</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="copyQuantity" class="btn btn-primary">Copy Quantities</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            $('#add-variant-btn').click(function() {
                $('#addVariantModal').modal('show');
            });

            $('.copy-quantity-btn').click(function() {
                let selectedColorId = $(this).attr('data-color-id');
                $(`#copy_quantity_for_color option`).show();
                $(`#copy_quantity_for_color option[value="${selectedColorId}"]`).hide();

                $('#copyQuantity').attr('data-selected-color-id', selectedColorId);
                $('#copyQuantityModal').modal('show');
            });

            $('#copyQuantity').click(function() {
                let colorIdForCopy = $('#copy_quantity_for_color').val();
                let colorIdToBeCopied = $(this).attr('data-selected-color-id');

                if(!colorIdForCopy){
                    $('#copy_quantity_for_color').addClass('is-invalid');
                    return;
                } else {
                    $('#copy_quantity_for_color').removeClass('is-invalid');
                }

                $('[data-size-id]').each(function() {
                    let sizeId = $(this).attr('data-size-id');
                    let valueToBeCopy = $(`[name="quantity[${colorIdToBeCopied}][${sizeId}]"]`).val();

                    $(`[name="quantity[${colorIdForCopy}][${sizeId}]"]`).val(valueToBeCopy);
                });
                
                $('#copy_quantity_for_color').val('');
                $('#copyQuantityModal').modal('hide');
            })
        })

        $(document).ready(function() {
            $('#saveVariantBtn').on('click', function() {
                $('#supplier_color_code').removeClass('is-invalid');
                $('#color_select').removeClass('is-invalid');

                let formData = {
                    supplier_color_code: $('#supplier_color_code').val(),
                    color_select: $('#color_select').val(),
                    product_id: '{{ isset($product) ? $product->id : 0 }}',
                    _token: '{{ csrf_token() }}'
                };

                $.post('/add-variant', formData).done(function(response) {
                        if (response.success) {
                            $('#addVariantModal').modal('hide');
                            $('#addVariantForm')[0].reset();
                            $('.actionColumn').removeClass('d-none');

                            let $tbody = $('table tbody');
                            let $sizeHeader = $('#sizeHeader');

                            let sizes = $sizeHeader.find('th')
                                .slice(1, -1) // Skip the first "Size" header and remove the last header
                                .map(function() {
                                    return $(this).attr('data-size-id');
                                })
                                .get();

                            let $newRow = $('<tr></tr>');
                            let $newTh = $('<th></th>').text(
                                `${response.data.color_name} (${response.data.color_code})`);
                            $newRow.append($newTh);

                            $.each(sizes, function(index, size) {
                                let $newTd = $('<td></td>');
                                $newTd.html(
                                    `<input type="number" name="quantity[${response.data.color_id}][${size}]" value="0" class="form-control">`
                                );
                                $newRow.append($newTd);
                            });

                            // Add delete button as the last cell
                            let $deleteTd = $('<td></td>');
                            $deleteTd.html(`
                            <a href="{{ route('products.remove-variant', '') }}/${response.data.color_id}" class="btn btn-danger"> 
                               <i class="fas fa-trash-alt"></i>
                            </a>`);

                            $newRow.append($deleteTd);

                            $tbody.prepend($newRow);
                        }
                    })
                    .fail(function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.supplier_color_code) {
                            $('#supplier_color_code').addClass('is-invalid');
                            $('#supplierColorCodeError').text(errors.supplier_color_code[0]);
                        }
                        if (errors.color_select) {
                            $('#color_select').addClass('is-invalid');
                            $('#colorSelectError').text(errors.color_select[0]);
                        }
                    });
            });
        });
    </script>
@endpush