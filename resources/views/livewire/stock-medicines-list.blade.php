<div x-data="{ isTableData: true, isInvoiceData: false  }">
    <div x-show="isTableData" x-transition x-cloak>
        <div class="row">
            <div class="col">
                <div class="collapse multi-collapse show" id="multiCollapseExample1">
                    <div class="card card-body" style="position: unset;">
                        <div class="row mt-3">
                            <div class="row justify-content-end">
                                <div class="col m-1 p-1">
                                    <h3 class="text-center">
                                        All Medicine Stock List with Batch NO
                                    </h3>
                                </div>
                                <div class="col-3">
                                    <input id="search" class="form-control" type="search" wire:model.live="search" placeholder="Search" aria-label="Search By Name">
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Medicine Details</th>
                                        <th>Batch NO</th>
                                        <th>Expiry Date</th>
                                        <th>Purchase Quantity</th>
                                        <th>Return to Depo</th>
                                        <th>Present Stock</th>
                                        <th>Price</th>
                                        <th>Stock Value</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_lists as $stock_list)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="nav-link text-dark row">
                                                    <div class="d-flex">
                                                        <span class="me-2" style="max-width: 50px; max-height: 50px;">
                                                            <img class="w-100 h-100 img-fluid img-thumbnail" src="{{ asset($stock_list->medicine->image_url ?? 'img/medicine-logo.png') }}" alt="">
                                                        </span>
                                                        <span class="lh-sm fw-medium text-nowrap" title="{{ $stock_list->medicine->name }}">
                                                            <span class="text-uppercase fw-bold">{{ $stock_list->medicine->name }} (<span style="color: rgb(177, 55, 181)">{{ $stock_list->medicine->price }} ৳</span>)</span>
                                                            <span class="d-block text-muted" style="font-size: 13px;">Generic name: {{ $stock_list->medicine->generic_name }}</span>
                                                            <span class="d-block text-muted" style="font-size: 13px;">Manufacturers: {{$stock_list->medicine->supplier}}</span>
                                                        </span>
                                                    </div>
                                                </span>
                                            </td>
                                            <td>{{ $stock_list->batch_number }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($stock_list->expiry_date)->format('d M Y') }}
                                                <br>
                                                @if ($stock_list->expiry_date < now()->format('Y-m-d'))
                                                    <span class="text-danger">Expired</span>
                                                @else
                                                    <span class="text-success">
                                                        {{ \Carbon\Carbon::parse($stock_list->expiry_date)->diffForHumans(null, false, false, 2) }} left
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $stock_list->initial_quantity }}</td>
                                            <td>{{ $stock_list->stockReturnList->sum('quantity') }}</td>
                                            <td>{{ $stock_list->quantity }}</td>
                                            <td>{{ $stock_list->buy_price }}</td>
                                            <td>{{ $stock_list->quantity * $stock_list->buy_price }}</td>
                                            {{-- <td>
                                                @can('edit-customer')
                                                    <button class="btn btn-sm btn-info" wire:click="edit()" @click="isOpen = true"><i class="bi bi-pencil-square"></i></button>
                                                @endcan
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $stock_lists->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
