<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row my-2">
        <div class="col-md-8">
            <div class="shadow p-3 mb-5 bg-white rounded">
                <div class="card-header bg-transparent border-0">
                    <h4>Reports</h4>
                </div>
                <div class="card-body">
                    <div class="row g-1">
                        <div class="col-lg-3 col-6">
                            <div class="card p-2 bg-info-subtle">
                                <div class="small-box d-flex justify-content-between flex-column">
                                    <div class="border rounded-circle p-2 bg-info fw-bold fs-4 text-white" style="width: 2em; height: 2em;">
                                        <i class="bi bi-capsule-pill"></i>
                                    </div>
                                    <div class="inner text-center">
                                        <h3>{{$total_medicine}}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('medicines') }}" class="nav-link">
                                    <span>Stock Medicine</span> <i class="bi bi-arrow-right-circle-fill"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card p-2 bg-success-subtle">
                                <div class="small-box d-flex justify-content-between flex-column">
                                    <div class="border rounded-circle p-2 bg-success fw-bold fs-4 text-white" style="width: 2em; height: 2em;">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="inner text-center">
                                        <h3>৳ {{$total_sales}}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('sales-medicines-list') }}" class="nav-link">
                                    <span>Total Sales</span> <i class="bi bi-arrow-right-circle-fill"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card p-2 bg-danger-subtle">
                                <div class="small-box d-flex justify-content-between flex-column">
                                    <div class="border rounded-circle p-2 bg-danger fw-bold fs-4 text-white" style="width: 2em; height: 2em;">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="inner text-center">
                                        <h3>৳ {{$total_purchases}}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('stock-medicines-list') }}" class="nav-link">
                                    <span>Total Purchases</span> <i class="bi bi-arrow-right-circle-fill"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card p-2 bg-secondary-subtle">
                                <div class="small-box d-flex justify-content-between flex-column">
                                    <div class="border rounded-circle p-2 bg-secondary fw-bold fs-4 text-white" style="width: 2em; height: 2em;">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="inner text-center">
                                        <h3>{{$total_customers}}</h3>
                                    </div>
                                </div>
                                <a href="{{ route('customers') }}" class="nav-link">
                                    <span>Total Customers</span> <i class="bi bi-arrow-right-circle-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 border-r20 mb-3">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title">Purchases &amp; Sales</h4>
                </div>
                <div class="card-body">
                    <div id="line-example" style="height: 180px; color: red; position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);" class="line-atl morris-chart"><svg height="180" version="1.1" width="346" xmlns="http://www.w3.org/2000/svg" style="overflow: hidden; position: relative; left: -0.625px; top: -0.574997px;"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.0</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><text x="36.42500114440918" y="141.39999961853027" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal"><tspan dy="4.400002479553223" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan></text><path fill="none" stroke="#aaaaaa" d="M48.92500114440918,141.39999961853027H321.062" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="36.42500114440918" y="112.2999997138977" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal"><tspan dy="4.400001049041748" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0.25</tspan></text><path fill="none" stroke="#aaaaaa" d="M48.92500114440918,112.2999997138977H321.062" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="36.42500114440918" y="83.19999980926514" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal"><tspan dy="4.399999618530273" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0.5</tspan></text><path fill="none" stroke="#aaaaaa" d="M48.92500114440918,83.19999980926514H321.062" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="36.42500114440918" y="54.09999990463257" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal"><tspan dy="4.399998188018799" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0.75</tspan></text><path fill="none" stroke="#aaaaaa" d="M48.92500114440918,54.09999990463257H321.062" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="36.42500114440918" y="25" text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal"><tspan dy="4.399999618530273" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">1</tspan></text><path fill="none" stroke="#aaaaaa" d="M48.92500114440918,25H321.062" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="282.18528587777274" y="153.89999961853027" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal" transform="matrix(1,0,0,1,0,6.8)"><tspan dy="4.400002479553223" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2025-01-26</tspan></text><text x="165.55514351109096" y="153.89999961853027" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal" transform="matrix(1,0,0,1,0,6.8)"><tspan dy="4.400002479553223" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2025-01-23</tspan></text><text x="48.92500114440918" y="153.89999961853027" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 12px sans-serif;" font-size="12px" font-family="sans-serif" font-weight="normal" transform="matrix(1,0,0,1,0,6.8)"><tspan dy="4.400002479553223" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2025-01-20</tspan></text><path fill="none" stroke="#7a92a3" d="M48.92500114440918,25C58.644179674966,25,78.08253673607963,10.450000047683714,87.80171526663645,25C97.52089379719327,39.549999952316284,116.95925085830689,126.84999966621399,126.6784293888637,141.39999961853027C136.39760791942052,141.39999961853027,155.83596498053416,141.39999961853027,165.55514351109096,141.39999961853027C175.2743220416478,141.39999961853027,194.7126791027614,141.39999961853027,204.43185763331823,141.39999961853027C214.15103616387503,141.39999961853027,233.58939322498867,141.39999961853027,243.30857175554547,141.39999961853027C253.02775028610228,141.39999961853027,272.46610734721594,141.39999961853027,282.18528587777274,141.39999961853027C291.90446440832955,141.39999961853027,311.3428214694432,141.39999961853027,321.062,141.39999961853027" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="none" stroke="#0b62a4" d="M48.92500114440918,141.39999961853027C58.644179674966,112.2999997138977,78.08253673607963,25,87.80171526663645,25C97.52089379719327,25,116.95925085830689,126.84999966621399,126.6784293888637,141.39999961853027C136.39760791942052,141.39999961853027,155.83596498053416,141.39999961853027,165.55514351109096,141.39999961853027C175.2743220416478,141.39999961853027,194.7126791027614,141.39999961853027,204.43185763331823,141.39999961853027C214.15103616387503,141.39999961853027,233.58939322498867,141.39999961853027,243.30857175554547,141.39999961853027C253.02775028610228,141.39999961853027,272.46610734721594,141.39999961853027,282.18528587777274,141.39999961853027C291.90446440832955,141.39999961853027,311.3428214694432,141.39999961853027,321.062,141.39999961853027" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><circle cx="48.92500114440918" cy="25" r="4" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="87.80171526663645" cy="25" r="4" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="126.6784293888637" cy="141.39999961853027" r="4" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="165.55514351109096" cy="141.39999961853027" r="7" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="204.43185763331823" cy="141.39999961853027" r="4" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="243.30857175554547" cy="141.39999961853027" r="4" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="282.18528587777274" cy="141.39999961853027" r="4" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="321.062" cy="141.39999961853027" r="4" fill="#7a92a3" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="48.92500114440918" cy="141.39999961853027" r="4" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="87.80171526663645" cy="25" r="4" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="126.6784293888637" cy="141.39999961853027" r="4" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="165.55514351109096" cy="141.39999961853027" r="7" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="204.43185763331823" cy="141.39999961853027" r="4" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="243.30857175554547" cy="141.39999961853027" r="4" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="282.18528587777274" cy="141.39999961853027" r="4" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle><circle cx="321.062" cy="141.39999961853027" r="4" fill="#0b62a4" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle></svg><div class="morris-hover morris-default-style" style="left: 125.161px; top: 53px;"><div class="morris-hover-row-label">2025-01-23</div><div class="morris-hover-point" style="color: #0b62a4">
            Sales:
            0
            </div><div class="morris-hover-point" style="color: #7A92A3">
            Purchase:
            0
            </div></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-2">
        <div class="col-md-6">
            <div class="card border-0 border-r20 mb-3">
                <div class="card-header bg-transparent border-0">
                    <h4 class="card-title">Supporter List</h4>
                </div>
                <div class="card-body">
                    <div id="svg-tree"></div>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap 5 Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Modal Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($low_stock_medicine != null)
                    <h3 class="text-center h3">Low Stock Medicine</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr class="thead-dark">
                                <th>SN</th>
                                <th>Medicine</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($low_stock_medicine as $medicine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if($stock_out_medicine != null)
                    <h3 class="text-center h3">Stock Out Medicine</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr class="thead-dark">
                                <th>SN</th>
                                <th>Medicine</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock_out_medicine as $medicine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if ($expired_medicine != null)
                    <h3 class="text-center h3">Expired Medicine</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr class="thead-dark">
                                <th>SN</th>
                                <th>Medicine</th>
                                <th>Batch</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expired_medicine as $medicine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->batch }}</td>
                                    <td>{{ date('Y-m-d', strtotime($medicine->expiry_date)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if ($expire_alert_medicine != null)
                    <h3 class="text-center h3">Expire Alert Medicine</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr class="thead-dark">
                                <th>SN</th>
                                <th>Medicine</th>
                                <th>Batch</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expire_alert_medicine as $medicine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->batch }}</td>
                                    <td>{{ date('Y-m-d', strtotime($medicine->expiry_date)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    {{-- <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const data = @json($hierarchy);
            const options = {
                contentKey: 'data',
                width: 800,
                height: 600,
                nodeWidth: 150,
                nodeHeight: 100,
                fontColor: '#fff',
                borderColor: '#333',
                childrenSpacing: 50,
                siblingSpacing: 20,
                direction: 'top',
                enableExpandCollapse: true,
                nodeTemplate: (content) =>
                    `<div style='display: flex;flex-direction: column;gap: 10px;justify-content: center;align-items: center;height: 100%;'>
                        <img style='width: 50px;height: 50px;border-radius: 50%;' src='${content.imageURL}' alt='' />
                        <div style="font-weight: bold; font-family: Arial; font-size: 14px">${content.name}</div>
                        <div style="font-size: 12px; color: #999">${content.role}</div>
                    </div>`,
                canvasStyle: 'border: 1px solid black;background: #f6f6f6;',
                enableToolbar: true,
            };

            const tree = new ApexTree(document.getElementById('svg-tree'), options);
            tree.render(data);
        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var myModal = new window.bootstrap.Modal(document.getElementById('alertModal'));
            myModal.show();
        });
    </script>
@endpush
</x-app-layout>
