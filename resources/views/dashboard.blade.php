<x-app-layout>
    <div class="row sparkboxes mt-4 g-1">
        <div class="col-md-3 col-sm-6">
            <div class="box box1">
                <div class="details">
                    <h3>{{$total_medicine}}</h3>
                    <h4>Stock Medicine</h4>
                </div>
                <div id="spark1"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="box box2">
                <div class="details">
                    <h3>{{$total_sales}}</h3>
                    <h4>Total Sales</h4>
                </div>
                <div id="spark2"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="box box3">
                <div class="details">
                    <h3>{{$total_purchases}}</h3>
                    <h4>Total Purchases</h4>
                </div>
                <div id="spark3"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="box box4">
                <div class="details">
                    <h3>{{$total_customers}}</h3>
                    <h4>Total Customers</h4>
                </div>
                <div id="spark4"></div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-2">
        {{-- <div class="col-md-5">
            <div class="box shadow mt-4">
                <div id="radialBarBottom"></div>
            </div>
        </div> --}}
        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="box shadow mt-4">
                <div id="line-adwords" class=""></div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="box shadow mt-4">
                <div id="svg-tree"></div>
            </div>
        </div>
    </div>

    {{-- <div class="row mt-4">
        <div class="col-md-5">
        <div class="box shadow mt-4">
            <div id="barchart"></div>
        </div>
        </div>
        <div class="col-md-7">
        <div class="box shadow mt-4">
            <div id="areachart"></div>
        </div>
        </div>
    </div> --}}

<!-- Bootstrap 5 Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Modal Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
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



    @push('styles')
        <style>
            h1, h2, h3, h4, h5, h6, strong {
                font-weight: 600;
            }


            .content-area {
                max-width: 1280px;
                margin: 0 auto;
            }

            .box {
                background-color: #ffffff;
                padding: 25px 20px;
            }

            .shadow {
                box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08);
            }
            .sparkboxes .box {
                padding-top: 10px;
                padding-bottom: 10px;
                text-shadow: 0 1px 1px 1px #666;
                box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08);
                position: relative;
                border-radius: 5px;
            }

            .sparkboxes .box .details {
                position: absolute;
                color: #fff;
                transform: scale(0.7) translate(-22px, 20px);
            }
            .sparkboxes strong {
                position: relative;
                z-index: 3;
                top: -8px;
                color: #fff;
            }

            .sparkboxes .box1 {
                background-image: linear-gradient( 135deg, #ABDCFF 10%, #0396FF 100%);
            }

            .sparkboxes .box2 {
                background-image: linear-gradient( 135deg, #2AFADF 10%, #4C83FF 100%);
            }

            .sparkboxes .box3 {
                background-image: linear-gradient( 135deg, #FFD3A5 10%, #FD6585 100%);
            }

            .sparkboxes .box4 {
                background-image: linear-gradient( 135deg, #EE9AE5 10%, #5961F9 100%);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new window.bootstrap.Modal(document.getElementById('alertModal'));
                myModal.show();

                window.Apex = {
                    chart: {
                        foreColor: '#ccc',
                        toolbar: {
                            show: false
                        },
                    },
                    stroke: {
                        width: 3
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        theme: 'dark'
                    },
                    grid: {
                        borderColor: "#535A6C",
                        xaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                var spark1 = {
                    chart: {
                        id: 'spark1',
                        group: 'sparks',
                        type: 'line',
                        height: 80,
                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            top: 1,
                            left: 1,
                            blur: 2,
                            opacity: 0.5,
                        }
                    },
                    series: [{
                        data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 0
                    },
                    grid: {
                        padding: {
                        top: 20,
                        bottom: 10,
                        left: 110
                        }
                    },
                    colors: ['#fff'],
                    tooltip: {
                        x: {
                            show: false
                        },
                        y: {
                            title: {
                                formatter: function formatter(val) {
                                return '';
                                }
                            }
                        }
                    }
                }

                var spark2 = {
                    chart: {
                        id: 'spark2',
                        group: 'sparks',
                        type: 'line',
                        height: 80,
                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            top: 1,
                            left: 1,
                            blur: 2,
                            opacity: 0.5,
                        }
                    },
                    series: [{
                        data: [12, 14, 2, 47, 32, 44, 14, 55, 41, 69]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    grid: {
                        padding: {
                        top: 20,
                        bottom: 10,
                        left: 110
                        }
                    },
                    markers: {
                        size: 0
                    },
                    colors: ['#fff'],
                    tooltip: {
                        x: {
                        show: false
                        },
                        y: {
                        title: {
                            formatter: function formatter(val) {
                            return '';
                            }
                        }
                        }
                    }
                }

                var spark3 = {
                    chart: {
                        id: 'spark3',
                        group: 'sparks',
                        type: 'line',
                        height: 80,
                        sparkline: {
                            enabled: true
                        },
                        dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 2,
                        opacity: 0.5,
                        }
                    },
                    series: [{
                        data: [47, 45, 74, 32, 56, 31, 44, 33, 45, 19]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 0
                    },
                    grid: {
                        padding: {
                        top: 20,
                        bottom: 10,
                        left: 110
                        }
                    },
                    colors: ['#fff'],
                    xaxis: {
                        crosshairs: {
                        width: 1
                        },
                    },
                    tooltip: {
                        x: {
                        show: false
                        },
                        y: {
                        title: {
                            formatter: function formatter(val) {
                            return '';
                            }
                        }
                        }
                    }
                }

                var spark4 = {
                    chart: {
                        id: 'spark4',
                        group: 'sparks',
                        type: 'line',
                        height: 80,
                        sparkline: {
                        enabled: true
                        },
                        dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 2,
                        opacity: 0.5,
                        }
                    },
                    series: [{
                        data: [15, 75, 47, 65, 14, 32, 19, 54, 44, 61]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 0
                    },
                    grid: {
                        padding: {
                        top: 20,
                        bottom: 10,
                        left: 110
                        }
                    },
                    colors: ['#fff'],
                    xaxis: {
                        crosshairs: {
                        width: 1
                        },
                    },
                    tooltip: {
                        x: {
                            show: false
                        },
                        y: {
                        title: {
                            formatter: function formatter(val) {
                            return '';
                            }
                        }
                        }
                    }
                }

                new ApexCharts(document.querySelector("#spark1"), spark1).render();
                new ApexCharts(document.querySelector("#spark2"), spark2).render();
                new ApexCharts(document.querySelector("#spark3"), spark3).render();
                new ApexCharts(document.querySelector("#spark4"), spark4).render();

                // var optionsCircle4 = {
                //     chart: {
                //         type: 'radialBar',
                //         height: 345,
                //         width: 380,
                //     },
                //     plotOptions: {
                //         radialBar: {
                //         size: undefined,
                //         inverseOrder: true,
                //         hollow: {
                //             margin: 5,
                //             size: '48%',
                //             background: 'transparent',

                //         },
                //         track: {
                //             show: false,
                //         },
                //         startAngle: -180,
                //         endAngle: 180

                //         },
                //     },
                //     stroke: {
                //         lineCap: 'round'
                //     },
                //     series: [{{$total_medicine}}, {{$total_purchases}}, {{$total_sales}}],
                //     labels: ['Medicine', 'Purchases', 'Sales'],
                //     legend: {
                //         show: true,
                //         floating: true,
                //         position: 'right',
                //         offsetX: 70,
                //         offsetY: 230
                //     },
                // }

                // var chartCircle4 = new ApexCharts(document.querySelector('#radialBarBottom'), optionsCircle4);
                // chartCircle4.render();


                var optionsLine = {
                    chart: {
                        height: 330,
                        type: 'line',
                        zoom: {
                            enabled: true
                        },
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 2,
                            blur: 4,
                            opacity: 1,
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    // colors: ["#3F51B5", '#2196F3'],
                    series: @json($series),
                    title: {
                        text: 'Last 7 Days Sales History for All Medicines',
                        align: 'left',
                        offsetY: 25,
                        offsetX: 20
                    },
                    subtitle: {
                        text: 'Statistics',
                        offsetY: 50,
                        offsetX: 20
                    },
                    markers: {
                        size: 6,
                        strokeWidth: 0,
                        hover: {
                        size: 9
                        }
                    },
                    grid: {
                        show: true,
                        padding: {
                        bottom: 0
                        }
                    },
                    // labels: ['01/15/2002', '01/16/2002', '01/17/2002', '01/18/2002', '01/19/2002', '01/20/2002'],
                    xaxis: {
                        categories: @json($dates)
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        offsetY: -20,
                        offsetX: 50
                    }
                }

                var chartLine = new ApexCharts(document.querySelector('#line-adwords'), optionsLine);
                chartLine.render();


                // var optionsBar = {
                //     chart: {
                //         height: 380,
                //         type: 'bar',
                //         stacked: true,
                //     },
                //     plotOptions: {
                //         bar: {
                //         columnWidth: '30%',
                //         horizontal: false,
                //         },
                //     },
                //     series: [{
                //         name: 'PRODUCT A',
                //         data: [14, 25, 21, 17, 12, 13, 11, 19]
                //     }, {
                //         name: 'PRODUCT B',
                //         data: [13, 23, 20, 8, 13, 27, 33, 12]
                //     }, {
                //         name: 'PRODUCT C',
                //         data: [11, 17, 15, 15, 21, 14, 15, 13]
                //     }],
                //     xaxis: {
                //         categories: ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2', '2012 Q3', '2012 Q4'],
                //     },
                //     fill: {
                //         opacity: 1
                //     },
                // }
                // var chartBar = new ApexCharts(document.querySelector("#barchart"),optionsBar);
                // chartBar.render();

                // var optionsArea = {
                //     chart: {
                //         height: 380,
                //         type: 'area',
                //         stacked: false,
                //     },
                //     stroke: {
                //         curve: 'straight'
                //     },
                //     series: [{
                //         name: "Music",
                //         data: [11, 15, 26, 20, 33, 27]
                //         },
                //         {
                //         name: "Photos",
                //         data: [32, 33, 21, 42, 19, 32]
                //         },
                //         {
                //         name: "Files",
                //         data: [20, 39, 52, 11, 29, 43]
                //         }
                //     ],
                //     xaxis: {
                //         categories: ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2'],
                //     },
                //     tooltip: {
                //         followCursor: true
                //     },
                //     fill: {
                //         opacity: 1,
                //     },
                // }

                // var chartArea = new ApexCharts(document.querySelector("#areachart"),optionsArea);
                // chartArea.render();
                function renderTree() {
                    var containerWidth = $('#svg-tree').width(); // always get latest width

                    const options = {
                        contentKey: 'data',
                        width: containerWidth,
                        height: 350,
                        nodeWidth: 250,
                        nodeHeight: 60,
                        fontColor: '#fff',
                        borderColor: '#333',
                        childrenSpacing: 50,
                        siblingSpacing: 20,
                        direction: 'top',
                        enableExpandCollapse: true,
                        nodeTemplate: (content) =>
                            `<div class="row p-2">
                                <div class="col-3">
                                    <img style='width: 45px;height: 45px;border-radius: 50%;' src='${content.imageURL}' alt=''>
                                </div>
                                <div class="col-9">
                                    <div class="fw-bold" style="font-family: Arial; font-size: 12px">Name: ${content.name}</div>
                                    <div style="font-family: Arial; font-size: 10px">Role: ${content.role ?? ''}</div>
                                </div>
                            </div>`,
                        canvasStyle: 'border: 1px solid black;background: #f6f6f6;',
                        enableToolbar: true,
                    };

                    const tree = new ApexTree(document.getElementById('svg-tree'), options);
                    tree.render(data); // global data, must be defined outside
                }

                const data = {!! $hierarchy !!}; // keep data accessible globally
                renderTree(); // Initial render

                // Re-render on window resize
                $(window).on('resize', function () {
                    $('#svg-tree').empty(); // Clear old canvas
                    renderTree();           // Re-render with new width
                });
            });
        </script>
        @endpush
</x-app-layout>
