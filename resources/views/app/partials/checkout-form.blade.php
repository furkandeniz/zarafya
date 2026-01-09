<div class="untree_co-section">
    <div class="container">

        <div class="row mb-5">
            <div class="col-md-12">
                <div class="border p-4 rounded" role="alert">
                    Returning customer? <a href="#">Click here</a> to login
                </div>
            </div>
        </div>

        <form method="POST" action="{{ url('/checkout') }}">
            @csrf

            <div class="row">
                <!-- LEFT -->
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>

                    <div class="p-3 p-lg-5 border bg-white">

                        <div class="form-group">
                            <label class="text-black">Country <span class="text-danger">*</span></label>
                            <select class="form-control">
                                <option>Select a country</option>
                                <option>Bangladesh</option>
                                <option>Algeria</option>
                                <option>Afghanistan</option>
                            </select>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="text-black">First Name *</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="text-black">Last Name *</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-black">Address *</label>
                            <input type="text" class="form-control" placeholder="Street address">
                        </div>

                        <div class="form-group">
                            <label class="text-black">Email *</label>
                            <input type="email" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="text-black">Phone *</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="text-black">Order Notes</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>

                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-md-6">

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border bg-white">

                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Product Name x 1</td>
                                        <td>$250.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold">
                                            <strong>Order Total</strong>
                                        </td>
                                        <td class="text-black font-weight-bold">
                                            <strong>$250.00</strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="border p-3 mb-3">
                                    <h3 class="h6 mb-0">
                                        <a class="d-block" data-bs-toggle="collapse" href="#payment1">
                                            Direct Bank Transfer
                                        </a>
                                    </h3>
                                    <div class="collapse" id="payment1">
                                        <div class="py-2">
                                            <p class="mb-0">Make your payment directly into our bank account.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border p-3 mb-5">
                                    <h3 class="h6 mb-0">
                                        <a class="d-block" data-bs-toggle="collapse" href="#payment2">
                                            Paypal
                                        </a>
                                    </h3>
                                    <div class="collapse" id="payment2">
                                        <div class="py-2">
                                            <p class="mb-0">Pay via PayPal.</p>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-black btn-lg py-3 btn-block">
                                    Place Order
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>
