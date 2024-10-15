<x-app-layout>

    <head>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>

    <div class="container testimonial">
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @elseif (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div id="testimonialSlider" class="carousel slide" data-bs-interval="false">
            <div class="carousel-inner" id="testimonials-container">
                <!-- Testimonials will be injected dynamically here -->
            </div>
        </div>

        <!-- Custom Buttons to control testimonials -->
        <div class="control-btns">
            <button id="prevTestimonial" class="btn btn-secondary">Back</button>
            <button id="addTestimonialBtn" class="btn btn-success">
                <i class="bi bi-plus-circle icon-btn" title="Add"></i>
            </button>
            <button id="nextTestimonial" class="btn btn-primary">Next</button>
        </div>
    </div>

    <!-- Modal for Adding New Testimonial -->
    <div class="modal fade" id="addTestimonialModal" tabindex="-1" aria-labelledby="addTestimonialLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTestimonialLabel">Add New Quote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('quote.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="text" class="form-label">Quote</label>
                            <textarea class="form-control animated-input" id="text" name="text" rows="4" required
                                placeholder="Enter the quote here...">{{ old('text') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="credit_to" class="form-label">Credit To</label>
                            <input type="text" class="form-control animated-input" id="credit_to" name="credit_to"
                                placeholder="Enter your name..." value="{{ old('credit_to') }}">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary cool-button">Add Testimonial</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Testimonial -->
    <div class="modal fade" id="editQuoteModal" tabindex="-1" aria-labelledby="editQuoteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuoteLabel">Edit Quote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('quote.update', ['id' => 0]) }}" id="editTestimonialForm">
                        @csrf
                        @method('PUT') <!-- Use PUT for updating -->
                        <div class="mb-4">
                            <label for="edit-text" class="form-label">Quote</label>
                            <textarea class="form-control animated-input" id="edit-text" name="text" rows="4"
                                required>{{ old('text') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="edit-credit_to" class="form-label">Credit To</label>
                            <input type="text" class="form-control animated-input" id="edit-credit_to" name="credit_to"
                                value="{{ old('credit_to') }}">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary cool-button">Update Testimonial</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Convert PHP data to JS
        const quotes = @json($quotes);
        const currentUserId = @json(auth()->id());

        // Function to load testimonials dynamically
        function loadTestimonials() {
            const container = document.getElementById('testimonials-container');
            container.innerHTML = ''; // Clear previous content

            quotes.forEach((testimonial, index) => {
                const activeClass = index === 0 ? 'active' : '';
                const isOwner = testimonial.user_id === currentUserId;
                const testimonialHTML = `
        <div class="carousel-item ${activeClass}">
            <div class="d-flex justify-content-center">
                <img src="${testimonial.image}" class="avatar" alt="${testimonial.name}">
            </div>
            <p class="quote">"${testimonial.text}"</p>
            <p class="author">${testimonial.credit_to ? testimonial.credit_to : 'Unknown'}</p>
            <p class="role">${testimonial.created_at}</p>

            <!-- Actions for like, edit, delete (visible only to owner) -->
            <div class="testimonial-actions">
                <i class="bi bi-hand-thumbs-up icon-btn" title="Like"></i>
                ${isOwner ? `
                <i class="bi bi-pencil icon-btn edit-btn" title="Edit" data-bs-toggle="modal" data-bs-target="#editQuoteModal"
                    data-text="${testimonial.text}" data-credit_to="${testimonial.credit_to}" data-id="${testimonial.id}"></i>
                <i class="bi bi-trash icon-btn delete-btn text-danger" title="Delete" data-id="${testimonial.id}"></i>
                ` : ''}
            </div>
        </div>
    `;
                container.innerHTML += testimonialHTML;
            });
        }

        // Load testimonials when the page loads
        document.addEventListener('DOMContentLoaded', loadTestimonials);

        // JavaScript to handle edit button click and populate the edit modal
        document.addEventListener('click', function (event) {
            if (event.target.matches('.edit-btn') || event.target.closest('.edit-btn')) {
                const button = event.target.closest('.edit-btn');
                const text = button.getAttribute('data-text');
                const creditTo = button.getAttribute('data-credit_to');
                const id = button.getAttribute('data-id');

                // Set the values in the edit form
                document.getElementById('edit-text').value = text;
                document.getElementById('edit-credit_to').value = creditTo;

                // Update the form action with the correct ID
                const editForm = document.getElementById('editTestimonialForm');
                editForm.action = `/quote/${id}`;
            }
        });

        // Handle delete button click
        document.addEventListener('click', function (event) {
            if (event.target.matches('.delete-btn') || event.target.closest('.delete-btn')) {
                const deleteButton = event.target.closest('.delete-btn');
                const id = deleteButton.getAttribute('data-id');

                if (confirm('Are you sure you want to delete this testimonial?')) {
                    // Create a form dynamically to submit the DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/quote/${id}`;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    form.appendChild(methodInput);
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();  // Submit the form to delete the testimonial
                }
            }
        });

        // Initialize Bootstrap Carousel
        const carousel = new bootstrap.Carousel(document.querySelector('#testimonialSlider'), {
            interval: false  // Disable auto-slide
        });

        // Go to the next slide
        document.getElementById('nextTestimonial').addEventListener('click', function () {
            carousel.next();
        });

        // Go to the previous slide
        document.getElementById('prevTestimonial').addEventListener('click', function () {
            carousel.prev();
        });

        // Handle form submission for adding new testimonial
        document.getElementById('addTestimonialBtn').addEventListener('click', function () {
            var addTestimonialModal = new bootstrap.Modal(document.getElementById('addTestimonialModal'));
            addTestimonialModal.show();
        });
    </script>
</x-app-layout>
