@extends('layouts.app')

@section('title', 'New Transaction | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">New Order</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Create a new service transaction</p>
        </div>
        
        <!-- Back Button -->
        <a href="{{ route('transactions.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to List</span>
        </a>
    </header>

    <!-- Master Form -->
    <form method="POST" action="{{ route('transactions.store') }}" id="transaction-form" class="space-y-6 pb-12">
        @csrf

        <!-- STEP 1: CUSTOMER SELECTION -->
        <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-blue-600/30">1</div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Select Customer</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Search -->
                <div class="relative">
                    <label for="customer_search" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1 mb-1">Search Customer</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="customer_search"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                            placeholder="Type name or phone..."
                            autocomplete="off"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Dropdown Results -->
                    <div id="customer_list_container" class="absolute w-full mt-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-xl max-h-60 overflow-y-auto z-20 hidden custom-scrollbar">
                        <!-- JS Injects items here -->
                    </div>
                </div>

                <!-- Selected Customer Display -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1 mb-1">Selected Customer</label>
                    <div id="selected_customer_display" class="block w-full px-4 py-3 bg-blue-50 dark:bg-blue-900/10 border-2 border-blue-100 dark:border-blue-900/30 rounded-xl min-h-[50px] flex flex-col justify-center">
                        <span class="text-gray-400 dark:text-gray-500 italic text-sm">No customer selected...</span>
                    </div>
                    
                    <input type="hidden" id="CustomerID" name="CustomerID">
                    @error('CustomerID')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Quick Add Button -->
            <div class="text-right mt-4">
                <a href="{{ route('customers.create') }}" class="inline-flex items-center text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create New Profile
                </a>
            </div>
        </div>

        <!-- STEP 2: ADD SERVICES -->
        <div id="step_2_container" class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 opacity-50 transition-opacity duration-300">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center font-extrabold text-lg">2</div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add Services</h2>
            </div>
            
            <div id="step_2_content">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Service Select -->
                    <div class="md:col-span-2 space-y-1">
                        <label for="service_select" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Service Type</label>
                        <div class="relative">
                            <select id="service_select" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium appearance-none" disabled>
                                <option value="">Select a service...</option>
                                @foreach($services as $service)
                                    @php
                                        $unit = strtolower($service->Unit);
                                        $rawPrice = $service->BasePrice ?? $service->Price;
                                        $minQty = $service->MinQuantity > 0 ? $service->MinQuantity : 1;
                                        
                                        // If kg (Washing), calculate Price per Load (Price/Kg * Capacity)
                                        // Otherwise, use standard Unit Price
                                        $effectivePrice = ($unit == 'kg') ? ($rawPrice * $minQty) : $rawPrice;
                                        $displayUnit = ($unit == 'kg') ? 'load' : $service->Unit;
                                    @endphp
                                    <option value="{{ $service->ServiceID }}" 
                                            data-price="{{ $effectivePrice }}" 
                                            data-unit="{{ $service->Unit }}"
                                            data-min-quantity="{{ $service->MinQuantity ?? 0 }}">
                                            {{ $service->Name }} (₱{{ number_format($effectivePrice, 2) }} / {{ $displayUnit }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Input -->
                    <div id="quantity_input_container" class="hidden space-y-1">
                        <label for="service_quantity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Quantity</label>
                        <input type="number" id="service_quantity" min="1" value="1" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none" disabled>
                    </div>

                    <!-- Weight Input -->
                    <div id="weight_input_container" class="hidden space-y-1">
                        <label for="service_weight" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Weight (kg)</label>
                        <input type="number" id="service_weight" step="0.01" min="0.1" value="1.0" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none" disabled>
                    </div>

                    <!-- Add Button -->
                    <div>
                        <button type="button" id="add_to_cart_btn" class="w-full h-[46px] flex items-center justify-center px-4 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-bold rounded-xl shadow-md transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add
                        </button>
                    </div>
                </div>
            </div>
            
            <p id="step_2_placeholder" class="text-gray-400 dark:text-gray-500 text-sm font-medium mt-4 italic bg-gray-50 dark:bg-gray-700/30 p-3 rounded-xl text-center">Please select a customer to unlock services.</p>
        </div>

        <!-- STEP 3: ORDER SUMMARY -->
        <div id="step_3_container" class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 opacity-50 transition-opacity duration-300">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center font-extrabold text-lg">3</div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Order Summary</h2>
            </div>

            <!-- Cart Table -->
            <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-700 mb-6">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Qty/Wt</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Price/Load</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Subtotal</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody id="cart_table_body" class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        <tr id="cart_placeholder_row">
                            <td colspan="5" class="px-4 py-8 text-sm text-gray-400 dark:text-gray-500 text-center font-medium">
                                Cart is empty. Add a service above.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Hidden Inputs -->
            <div id="cart_hidden_inputs"></div>

            <!-- Total Display -->
            <div class="flex justify-end items-center gap-4">
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Payable</p>
                <p id="total_amount_display" class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">₱0.00</p>
                <input type="hidden" name="TotalAmount" id="TotalAmountInput" value="0">
            </div>
        </div>


        <!-- STEP 4: FINAL DETAILS -->
        <div id="step_4_container" class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 opacity-50 transition-opacity duration-300">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center font-extrabold text-lg">4</div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Finalize Order</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Status -->
                <div class="space-y-1">
                    <label for="PaymentStatus" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Payment Status</label>
                    <div class="relative">
                        <select id="PaymentStatus" name="PaymentStatus" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium appearance-none" disabled>
                            <option value="Unpaid" selected>Unpaid</option>
                            <option value="Paid">Paid</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2 space-y-1">
                    <label for="Notes" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Notes <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <textarea id="Notes" name="Notes" rows="3" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400" placeholder="Special instructions..." disabled></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" id="submit_transaction_btn" class="w-full py-4 px-6 bg-green-500 hover:bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-500/30 transition-all transform hover:scale-[1.01] active:scale-[0.99] text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none" disabled>
                    Confirm & Submit Transaction
                </button>
            </div>
        </div>

    </form>

    <!-- JavaScript for Cart -->
    <script>
        const allCustomers = @json($customers);
        const allServices = @json($services);
        let cartItems = [];

        const searchInput = document.getElementById('customer_search');
        const listContainer = document.getElementById('customer_list_container');
        const selectedDisplay = document.getElementById('selected_customer_display');
        const customerIdInput = document.getElementById('CustomerID');

        const step2Container = document.getElementById('step_2_container');
        const step2Placeholder = document.getElementById('step_2_placeholder');
        const serviceSelect = document.getElementById('service_select');
        const quantityInputContainer = document.getElementById('quantity_input_container');
        const quantityInput = document.getElementById('service_quantity');
        const weightInputContainer = document.getElementById('weight_input_container');
        const weightInput = document.getElementById('service_weight');
        const addToCartBtn = document.getElementById('add_to_cart_btn');

        const step3Container = document.getElementById('step_3_container');
        const cartTableBody = document.getElementById('cart_table_body');
        const cartPlaceholderRow = document.getElementById('cart_placeholder_row');
        const totalAmountDisplay = document.getElementById('total_amount_display');
        const totalAmountInput = document.getElementById('TotalAmountInput');
        const cartHiddenInputs = document.getElementById('cart_hidden_inputs');

        const step4Container = document.getElementById('step_4_container');
        const paymentStatusSelect = document.getElementById('PaymentStatus');
        const notesTextarea = document.getElementById('Notes');
        const submitTransactionBtn = document.getElementById('submit_transaction_btn');

        searchInput.addEventListener('keyup', (e) => {
            const query = e.target.value.toLowerCase();
            if (query.length < 1) {
                listContainer.innerHTML = '';
                listContainer.classList.add('hidden');
                return;
            }
            const filteredCustomers = allCustomers.filter(customer => {
                return customer.Name.toLowerCase().includes(query) || 
                       customer.ContactNumber.toLowerCase().includes(query);
            });
            listContainer.innerHTML = ''; 
            if (filteredCustomers.length > 0) {
                filteredCustomers.forEach(customer => {
                    const item = document.createElement('div');
                    item.className = 'p-4 hover:bg-blue-50 dark:hover:bg-blue-900/20 cursor-pointer border-b border-gray-100 dark:border-gray-700 transition-colors';
                    item.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-500 dark:text-gray-300">
                                ${customer.Name.charAt(0)}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm">${customer.Name}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">${customer.ContactNumber}</p>
                            </div>
                        </div>
                    `;
                    item.addEventListener('click', () => {
                        selectCustomer(customer);
                    });
                    listContainer.appendChild(item);
                });
                listContainer.classList.remove('hidden');
            } else {
                listContainer.innerHTML = '<div class="p-4 text-gray-400 text-sm italic text-center">No customers found. <a href="{{ route("customers.create") }}" class="text-blue-500 hover:underline">Create new?</a></div>';
                listContainer.classList.remove('hidden');
            }
        });

        function selectCustomer(customer) {
            selectedDisplay.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">
                            ${customer.Name.charAt(0)}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white">${customer.Name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${customer.ContactNumber}</p>
                        </div>
                    </div>
                    <button type="button" onclick="resetCustomer()" class="text-xs text-red-500 hover:text-red-700 font-bold uppercase tracking-wide">Change</button>
                </div>
            `;
            
            selectedDisplay.classList.remove('bg-gray-100', 'border-gray-200');
            selectedDisplay.classList.add('bg-white', 'dark:bg-gray-800', 'border-blue-500', 'ring-4', 'ring-blue-500/10');

            customerIdInput.value = customer.CustomerID;
            searchInput.value = '';
            listContainer.classList.add('hidden');
            
            enableSteps(true);
        }

        function resetCustomer() {
            selectedDisplay.innerHTML = '<span class="text-gray-400 dark:text-gray-500 italic text-sm">No customer selected...</span>';
            selectedDisplay.classList.add('bg-gray-100', 'border-gray-200');
            selectedDisplay.classList.remove('bg-white', 'dark:bg-gray-800', 'border-blue-500', 'ring-4', 'ring-blue-500/10');
            customerIdInput.value = '';
            enableSteps(false);
        }

        function enableSteps(enable) {
            if (enable) {
                step2Container.classList.remove('opacity-50');
                step2Placeholder.classList.add('hidden');
                step3Container.classList.remove('opacity-50');
                step4Container.classList.remove('opacity-50');
            } else {
                step2Container.classList.add('opacity-50');
                step2Placeholder.classList.remove('hidden');
                step3Container.classList.add('opacity-50');
                step4Container.classList.add('opacity-50');
            }
            
            serviceSelect.disabled = !enable;
            quantityInput.disabled = !enable;
            weightInput.disabled = !enable;
            addToCartBtn.disabled = !enable;
            paymentStatusSelect.disabled = !enable;
            notesTextarea.disabled = !enable;
            submitTransactionBtn.disabled = !enable;
        }

        serviceSelect.addEventListener('change', (e) => {
            const selectedOption = e.target.options[e.target.selectedIndex];
            quantityInputContainer.classList.add('hidden');
            weightInputContainer.classList.add('hidden');

            if (selectedOption.value) {
                const unit = selectedOption.getAttribute('data-unit').toLowerCase();
                if (unit === 'kg') {
                    weightInputContainer.classList.remove('hidden');
                } else if (unit === 'item' || unit === 'load') {
                    quantityInputContainer.classList.remove('hidden');
                }
            }
        });

        addToCartBtn.addEventListener('click', () => {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            if (!selectedOption.value) {
                alert('Please select a service.');
                return;
            }

            const serviceId = selectedOption.value;
            const serviceName = selectedOption.text.trim();
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            const unit = selectedOption.getAttribute('data-unit').toLowerCase();
            const minQuantity = parseFloat(selectedOption.getAttribute('data-min-quantity'));
            
            let actualQuantity = 0;
            let subtotal = 0;
            let loadInfo = '';
            
            if (unit === 'kg') {
                actualQuantity = parseFloat(weightInput.value);
            } else {
                actualQuantity = parseInt(quantityInput.value, 10);
            }

            if (isNaN(actualQuantity) || actualQuantity <= 0) {
                alert('Please enter a valid quantity or weight.');
                return;
            }

            // pricing logic
            if (unit === 'kg') {
                // Per Load
                const capacity = minQuantity > 0 ? minQuantity : 1;
                const loads = Math.ceil(actualQuantity / capacity);
                subtotal = loads * price;
                loadInfo = `(${loads} ${loads > 1 ? 'Loads' : 'Load'} @ ${capacity}kg/load)`;
            } else {
                // No Min enforcement
                subtotal = actualQuantity * price;
            }

            const cartItem = {
                id: Date.now(),
                serviceId: serviceId,
                serviceName: serviceName,
                quantity: actualQuantity,
                unit: unit,
                pricePerUnit: price,
                subtotal: subtotal,
                loadInfo: loadInfo 
            };
            cartItems.push(cartItem);

            renderCart();
            updateTotal();
            updateHiddenInputs();
            
            // Reset Input
            serviceSelect.selectedIndex = 0;
            quantityInput.value = 1;
            weightInput.value = 1.0;
            quantityInputContainer.classList.add('hidden');
            weightInputContainer.classList.add('hidden');
        });

        function renderCart() {
            if (cartPlaceholderRow) cartPlaceholderRow.remove();
            cartTableBody.innerHTML = '';

            cartItems.forEach(item => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors';
                row.innerHTML = `
                    <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white">
                        ${item.serviceName}
                        ${item.loadInfo ? `<span class="block text-xs text-blue-500 font-normal">${item.loadInfo}</span>` : ''}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">${item.quantity} ${item.unit}</td>
                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">₱${item.pricePerUnit.toFixed(2)}</td>
                    <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white">₱${item.subtotal.toFixed(2)}</td>
                    <td class="px-4 py-4 text-right">
                        <button type="button" onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
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
            if (cartItems.length === 0) {
                cartTableBody.innerHTML = `<tr id="cart_placeholder_row"><td colspan="5" class="px-4 py-8 text-sm text-gray-400 dark:text-gray-500 text-center font-medium">Cart is empty. Add a service above.</td></tr>`;
            }
        }

        function updateTotal() {
            const total = cartItems.reduce((sum, item) => sum + item.subtotal, 0);
            totalAmountDisplay.textContent = `₱${total.toFixed(2)}`;
            totalAmountInput.value = total.toFixed(2);
        }

        function updateHiddenInputs() {
            cartHiddenInputs.innerHTML = '';
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