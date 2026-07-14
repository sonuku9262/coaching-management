<div>

    <div class="container-fluid py-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Profit &amp; Loss Report</h4>

            <button class="btn btn-light" wire:click="export">
                ⬇ Export CSV
            </button>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-3">
                    <label>From</label>
                    <input type="date" class="form-control" wire:model.live="from_date">
                </div>

                <div class="col-md-3">
                    <label>To</label>
                    <input type="date" class="form-control" wire:model.live="to_date">
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body py-2">
                            <small>Total Income</small>
                            <h4 class="mb-0">₹ {{ number_format($income, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-2">
                            <small>Total Expense</small>
                            <h4 class="mb-0">₹ {{ number_format($totalExpense, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card {{ $netProfit >= 0 ? 'bg-primary' : 'bg-dark' }} text-white">
                        <div class="card-body py-2">
                            <small>Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</small>
                            <h4 class="mb-0">₹ {{ number_format(abs($netProfit), 2) }}</h4>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <div class="card">

                        <div class="card-header bg-success text-white">Income</div>

                        <div class="card-body">

                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td>Fee Collection</td>
                                        <td class="text-end">₹ {{ number_format($income, 2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="fw-bold">
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-end">₹ {{ number_format($income, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>

                </div>

                <div class="col-md-6 mb-3">

                    <div class="card">

                        <div class="card-header bg-danger text-white">Expenses</div>

                        <div class="card-body">

                            <table class="table table-bordered mb-0">
                                <tbody>
                                    @forelse($expenseByCategory as $category => $amount)
                                        <tr>
                                            <td>{{ $category }}</td>
                                            <td class="text-end">₹ {{ number_format($amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">No Expenses Recorded</td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <td>Staff Salary</td>
                                        <td class="text-end">₹ {{ number_format($salary, 2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="fw-bold">
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-end">₹ {{ number_format($totalExpense, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</div>
