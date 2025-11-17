@extends('layouts.app')
@section('title', 'New Transaction | Sip Laundry')
@section('content')
    <!-- Page Header -->
    <h1 class="text-3xl font-bold text-gray-900 mb-6">
        Create New Transaction
    </h1>

    <!-- Master Form: This form will wrap all steps -->
    <form method="POST" action="{{ route('transactions.store') }}" id="transaction-form" class="space-y-8">
        @csrf

        <!-- ********************************************** -->
        <!-- STEP 1: CUSTOMER SELECTION -->
        <!-- ********************************************** -->
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-3">
                <span class="inline-flex items-center justify-center bg-indigo-600 text-white rounded-full h-8 w-8 mr-2">1</span>
                Select Customer
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Search -->
                <div>
                    <label for="customer_search" class="block text-sm font-medium text-gray-700 mb-1">Search Customer (by Name or Phone)</label>
                    <input
                        type="text"
                        id="customer_search"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Start typing a name or phone number..."
                        autocomplete="off"
                    >
                    
                    <!-- Customer List (Hidden by default, shown by JS) -->
                    <div id="customer_list_container" class="mt-2 border border-gray-300 rounded-lg max-h-48 overflow-y-auto bg-white absolute z-10 w-full md:w-1/3 hidden shadow-lg">
                        <!-- Customer items will be injected here by JavaScript -->
                    </div>
                </div>

                <!-- Selected Customer Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selected Customer</label>
                    <div id="selected_customer_display" class="mt-1 block w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg min-h-[42px]">
                        <span class="text-gray-500 italic">No customer selected...</span>
                    </div>
                    
                    <!-- Hidden input to store the CustomerID -->
                    <input type="hidden" id="CustomerID" name="CustomerID">

                    <!-- Error message for customer selection -->
                    @error('CustomerID')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Add New Customer Button (Placeholder) -->
            <div class="text-right mt-4">
                <button type="button" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                    + Add New Customer
                </button>
            </div>
        </div>

        <!-- ********************************************** -->
        <!-- STEP 2: ADD SERVICES (NOW FUNCTIONAL) -->
        <!-- ********************************************** -->
        <div id="step_2_container" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 opacity-50"> <!-- Faded out by default -->
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-3">
                <span class="inline-flex items-center justify-center bg-indigo-600 text-white rounded-full h-8 w-8 mr-2">2</span>
                Add Services
            </h2>
            
            <div id="step_2_content">
                <!-- Service Adder Form -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Service Select -->
                    <div class="md:col-span-2">
                        <label for="service_select" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select id="service_select" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" disabled>
                            <option value="">Select a service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->ServiceID }}" 
                                        data-price="{{ $service->BasePrice }}" 
                                        data-unit="{{ $service->Unit }}"
                                        data-min-quantity="{{ $service->MinQuantity ?? 0 }}">
                                    {{ $service->Name }} (₱{{ number_format($service->BasePrice, 2) }} / {{ $service->Unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity (for "item") -->
                    <div id="quantity_input_container" class="hidden">
                        <label for="service_quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" id="service_quantity" min="1" value="1" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" disabled>
                    </div>

                    <!-- Weight (for "kg") -->
                    <div id="weight_input_container" class="hidden">
                        <label for="service_weight" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                        <input type="number" id="service_weight" step="0.01" min="0.1" value="1.0" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" disabled>
                    </div>

                    <!-- Add to Order Button -->
                    <div>
                        <button type="button" id="add_to_cart_btn" class="w-full h-[42px] flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" disabled>
                            Add to Order
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- This placeholder text will be removed by JS -->
            <p id="step_2_placeholder" class="text-gray-500 italic mt-4">Please select a customer to enable this section.</p>

        </div>

        <!-- ********************************************** -->
        <!-- STEP 3: ORDER SUMMARY (NOW FUNCTIONAL) -->
        <!-- ********************************************** -->
        <div id="step_3_container" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 opacity-50">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-3">
                <span class="inline-flex items-center justify-center bg-indigo-600 text-white rounded-full h-8 w-8 mr-2">3</span>
                Order Summary
            </h2>

            <!-- Cart Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty/Weight</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="cart_table_body" class="bg-white divide-y divide-gray-200">
                        <tr id="cart_placeholder_row">
                            <td colspan="5" class="px-4 py-4 text-sm text-gray-500 text-center italic">No services added yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Hidden Inputs Container -->
            <div id="cart_hidden_inputs"></div>

            <!-- Total Amount Display -->
            <div class="mt-6 text-right">
                <p class="text-sm text-gray-500">Total Amount</p>
                <p id="total_amount_display" class="text-3xl font-bold text-gray-900">₱0.00</p>
                <input type="hidden" name="TotalAmount" id="TotalAmountInput" value="0">
            </div>
        </div>

        <!-- ********************************************** -->
        <!-- STEP 4: FINAL DETAILS -->
        <!-- ********************************************** -->
        <div id="step_4_container" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 opacity-50">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-3">
                <span class="inline-flex items-center justify-center bg-indigo-600 text-white rounded-full h-8 w-8 mr-2">4</span>
                Final Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Status -->
                <div>
                    <label for="PaymentStatus" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <select id="PaymentStatus" name="PaymentStatus" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" disabled>
                        <option value="Unpaid" selected>Unpaid</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="Notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea id="Notes" name="Notes" rows="3" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Customer requests extra softener." disabled></textarea>
                </div>
            </div>

            <!-- Final Submit Button -->
            <div class="mt-6 border-t pt-6">
                <button type="submit" id="submit_transaction_btn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" disabled>
                    Submit Transaction
                </button>
            </div>
        </div>

    </form>

    <!-- JavaScript -->
    <script>
        const allCustomers = @json($customers);
        const allServices = @json($services);

        // --- STATE ---
        let cartItems = []; // This will hold our "cart"

        // --- STEP 1 DOM ELEMENTS ---
        const searchInput = document.getElementById('customer_search');
        const listContainer = document.getElementById('customer_list_container');
        const selectedDisplay = document.getElementById('selected_customer_display');
        const customerIdInput = document.getElementById('CustomerID');

        // --- STEP 2 DOM ELEMENTS ---
        const step2Container = document.getElementById('step_2_container');
        const step2Placeholder = document.getElementById('step_2_placeholder');
        const serviceSelect = document.getElementById('service_select');
        const quantityInputContainer = document.getElementById('quantity_input_container');
        const quantityInput = document.getElementById('service_quantity');
        const weightInputContainer = document.getElementById('weight_input_container');
        const weightInput = document.getElementById('service_weight');
        const addToCartBtn = document.getElementById('add_to_cart_btn');

        // --- STEP 3 DOM ELEMENTS ---
        const step3Container = document.getElementById('step_3_container');
        const cartTableBody = document.getElementById('cart_table_body');
        const cartPlaceholderRow = document.getElementById('cart_placeholder_row');
        const totalAmountDisplay = document.getElementById('total_amount_display');
        const totalAmountInput = document.getElementById('TotalAmountInput');
        const cartHiddenInputs = document.getElementById('cart_hidden_inputs');

        // --- STEP 4 DOM ELEMENTS ---
        const step4Container = document.getElementById('step_4_container');
        const paymentStatusSelect = document.getElementById('PaymentStatus');
        const notesTextarea = document.getElementById('Notes');
        const submitTransactionBtn = document.getElementById('submit_transaction_btn');


        // --- STEP 1 JAVASCRIPT ---
        searchInput.addEventListener('keyup', (e) => {
            const query = e.target.value.toLowerCase();

            // Clear list if query is empty
            if (query.length < 1) {
                listContainer.innerHTML = '';
                listContainer.classList.add('hidden');
                return;
            }

            // Filter customers
            const filteredCustomers = allCustomers.filter(customer => {
                return customer.Name.toLowerCase().includes(query) || 
                       customer.ContactNumber.toLowerCase().includes(query);
            });

            // Populate list
            listContainer.innerHTML = ''; // Clear old results
            if (filteredCustomers.length > 0) {
                filteredCustomers.forEach(customer => {
                    const item = document.createElement('div');
                    item.className = 'p-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-100';
                    item.innerHTML = `<p class="font-medium">${customer.Name}</p><p class="text-sm text-gray-500">${customer.ContactNumber}</p>`;
                    
                    // Add click event to select the customer
                    item.addEventListener('click', () => {
                        selectCustomer(customer);
                    });
                    
                    listContainer.appendChild(item);
                });
                listContainer.classList.remove('hidden');
            } else {
                listContainer.innerHTML = '<div class="p-3 text-gray-500">No customers found.</div>';
                listContainer.classList.remove('hidden');
            }
        });

        function selectCustomer(customer) {
            selectedDisplay.innerHTML = `<p class="font-bold text-gray-800">${customer.Name}</p><p class="text-sm text-gray-500">${customer.ContactNumber}</p>`;
            customerIdInput.value = customer.CustomerID;
            searchInput.value = '';
            listContainer.classList.add('hidden');
            
            // --- ENABLE ALL OTHER STEPS ---
            step2Container.classList.remove('opacity-50');
            step2Placeholder.classList.add('hidden');
            serviceSelect.disabled = false;
            addToCartBtn.disabled = false;

            step3Container.classList.remove('opacity-50');
            step4Container.classList.remove('opacity-50');
            paymentStatusSelect.disabled = false;
            notesTextarea.disabled = false;
            submitTransactionBtn.disabled = false;
            
            // Re-enable inputs if a new customer is selected
            serviceSelect.disabled = false;
            quantityInput.disabled = false;
            weightInput.disabled = false;
        }

        // --- STEP 2 JAVASCRIPT ---
        serviceSelect.addEventListener('change', (e) => {
            const selectedOption = e.target.options[e.target.selectedIndex];
            
            // Hide both inputs first
            quantityInputContainer.classList.add('hidden');
            weightInputContainer.classList.add('hidden');

            if (selectedOption.value) {
                const unit = selectedOption.getAttribute('data-unit').toLowerCase();
                
                // Show the correct input based on the service unit
                if (unit === 'kg') {
                    weightInputContainer.classList.remove('hidden');
                } else if (unit === 'item' || unit === 'load') {
                    quantityInputContainer.classList.remove('hidden');
                }
            }
        });

        // --- NEW: STEP 3 JAVASCRIPT (CART LOGIC) ---
        addToCartBtn.addEventListener('click', () => {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            if (!selectedOption.value) {
                alert('Please select a service.');
                return;
            }

            const serviceId = selectedOption.value;
            const serviceName = selectedOption.text.split('(')[0].trim();
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            const unit = selectedOption.getAttribute('data-unit').toLowerCase();
            const minQuantity = parseFloat(selectedOption.getAttribute('data-min-quantity')); // <-- GET MIN QTY
            
            let actualQuantity = 0;
            let billableQuantity = 0; // <-- NEW
            let subtotal = 0;
            
            if (unit === 'kg') {
                actualQuantity = parseFloat(weightInput.value);
            } else {
                actualQuantity = parseInt(quantityInput.value, 10);
            }

            if (isNaN(actualQuantity) || actualQuantity <= 0) {
                alert('Please enter a valid quantity or weight.');
                return;
            }

            // --- NEW MINIMUM LOGIC ---
            if (actualQuantity < minQuantity) {
                billableQuantity = minQuantity;
                alert(`Note: The minimum for ${serviceName} is ${minQuantity} ${unit}. You will be charged for the minimum amount.`);
            } else {
                billableQuantity = actualQuantity;
            }
            // --- END NEW LOGIC ---

            subtotal = billableQuantity * price; // <-- Use billable quantity for price

            // Add item to our cart array
            const cartItem = {
                id: Date.now(), // Unique ID for this cart item
                serviceId: serviceId,
                serviceName: serviceName,
                quantity: actualQuantity, // <-- Store ACTUAL quantity
                unit: unit,
                pricePerUnit: price,
                subtotal: subtotal // <-- Store CALCULATED subtotal
            };
            cartItems.push(cartItem);

            renderCart();
            updateTotal();
            updateHiddenInputs();
            
            // Reset service form
            serviceSelect.selectedIndex = 0;
            quantityInput.value = 1;
            weightInput.value = 1.0;
            quantityInputContainer.classList.add('hidden');
            weightInputContainer.classList.add('hidden');
        });

        function renderCart() {
            // Remove placeholder
            if (cartPlaceholderRow) {
                cartPlaceholderRow.remove();
            }
            // Clear existing cart
            cartTableBody.innerHTML = '';

            cartItems.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-100';
                row.innerHTML = `
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">${item.serviceName}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">${item.quantity} ${item.unit}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">₱${item.pricePerUnit.toFixed(2)}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">₱${item.subtotal.toFixed(2)}</td>
                    <td class="px-4 py-3 text-right">
                        <button type="button" onclick="removeFromCart(${item.id})" class="text-red-600 hover:text-red-900 text-sm font-medium">Remove</button>
                    </td>
                `;
                cartTableBody.appendChild(row);
            });
        }

        function removeFromCart(itemId) {
            cartItems = cartItems.filter(item => item.id !== itemId);
            renderCart();
            updateTotal();
            updateHiddenInputs();

            // Add placeholder back if cart is empty
            if (cartItems.length === 0) {
                cartTableBody.innerHTML = `<tr id="cart_placeholder_row"><td colspan="5" class="px-4 py-4 text-sm text-gray-500 text-center italic">No services added yet.</td></tr>`;
            }
        }

        function updateTotal() {
            const total = cartItems.reduce((sum, item) => sum + item.subtotal, 0);
            totalAmountDisplay.textContent = `₱${total.toFixed(2)}`;
            totalAmountInput.value = total.toFixed(2);
        }

        // This function creates hidden inputs for the form submission
        function updateHiddenInputs() {
            cartHiddenInputs.innerHTML = ''; // Clear old inputs
            
            cartItems.forEach((item, index) => {
                cartHiddenInputs.innerHTML += `
                    <input type="hidden" name="cart_items[${index}][service_id]" value="${item.serviceId}">
                    <input type="hidden" name="cart_items[${index}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="cart_items[${index}][unit]" value="${item.unit}">
                    <input type="hidden" name="cart_items[${index}][price_per_unit]" value="${item.pricePerUnit}">
                    <input type="hidden" name="cart_items[${index}][subtotal]" value="${item.subtotal}">
                `;
            });
        }

    </script>
@endsection