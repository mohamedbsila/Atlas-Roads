<x-layouts.wishlist>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .animate-delay-100 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .animate-delay-200 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .animate-delay-300 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .animate-delay-400 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .animate-delay-500 {
            animation-delay: 0.5s;
            opacity: 0;
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(217, 70, 239, 0.1);
        }

        .priority-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .priority-card:hover {
            transform: translateX(10px);
            border-color: rgba(217, 70, 239, 0.3);
            box-shadow: 0 4px 12px rgba(217, 70, 239, 0.2);
        }

        .priority-card input:checked ~ * {
            background: linear-gradient(135deg, rgba(217, 70, 239, 0.1), rgba(236, 72, 153, 0.1));
        }

        .gradient-text {
            background: linear-gradient(135deg, #d946ef, #ec4899, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shimmer-effect {
            background: linear-gradient(90deg, 
                rgba(255,255,255,0) 0%, 
                rgba(255,255,255,0.5) 50%, 
                rgba(255,255,255,0) 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        .floating-icon {
            animation: bounce 2s infinite;
        }
    </style>

    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-6 animate-slide-in-left">
            <a href="{{ route('wishlist.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg"
               style="background:linear-gradient(135deg,#06b6d4,#3b82f6)">
                <i class="fas fa-arrow-left mr-2"></i> Back to My Wishlist
            </a>
        </div>

        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-2xl rounded-3xl bg-clip-border overflow-hidden animate-fade-in-up">
            <!-- Decorative gradient header background -->
            <div class="absolute top-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#d946ef,#ec4899,#f97316,#06b6d4,#3b82f6)"></div>
            
            <div class="border-b border-gray-200 p-8 relative">
                <div class="absolute right-8 top-8 floating-icon text-5xl opacity-10">
                    📚
                </div>
                <h5 class="mb-2 font-bold text-3xl gradient-text">Request a Book</h5>
                <p class="text-sm text-slate-500 mt-2 flex items-center">
                    <i class="fas fa-magic mr-2 text-purple-500"></i>
                    Fill in the book details you're looking for and we'll do the searching!
                </p>
            </div>

            <div class="p-8">
                <form action="{{ route('wishlist.store') }}" method="POST" enctype="multipart/form-data" id="wishlistForm">
                    @csrf

                    <!-- Title -->
                    <div class="mb-6 animate-fade-in-up animate-delay-100">
                        <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-book text-purple-500 mr-2"></i>
                            Book Title <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   placeholder="e.g., The Great Gatsby"
                                   class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-purple-500 @error('title') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-pencil-alt text-gray-300"></i>
                            </div>
                        </div>
                        @error('title')
                            <p class="mt-2 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Author -->
                    <div class="mb-6 animate-fade-in-up animate-delay-200">
                        <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-user-edit text-blue-500 mr-2"></i>
                            Author <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="author" value="{{ old('author') }}" required
                                   placeholder="e.g., F. Scott Fitzgerald"
                                   class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500 @error('author') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-feather text-gray-300"></i>
                            </div>
                        </div>
                        @error('author')
                            <p class="mt-2 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- ISBN (Optional) -->
                    <div class="mb-6 animate-fade-in-up animate-delay-300">
                        <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-barcode text-green-500 mr-2"></i>
                            ISBN <span class="text-gray-400 ml-1 font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="isbn" value="{{ old('isbn') }}"
                                   placeholder="978-3-16-148410-0"
                                   class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-green-500 @error('isbn') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-300"></i>
                            </div>
                        </div>
                        @error('isbn')
                            <p class="mt-2 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                            If you know the ISBN, it will help us find the exact book faster
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6 animate-fade-in-up animate-delay-400">
                        <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-comment-dots text-pink-500 mr-2"></i>
                            Why do you want this book? <span class="text-gray-400 ml-1 font-normal">(Optional)</span>
                        </label>
                        <textarea name="description" rows="4"
                                  placeholder="Tell us why this book is important to you..."
                                  class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-pink-500 resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-heart text-red-400 mr-1"></i>
                            Share your motivation - it helps us prioritize!
                        </p>
                    </div>

                    <!-- Book Cover Image -->
                    <div class="mb-6 animate-fade-in-up animate-delay-400">
                        <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-image text-indigo-500 mr-2"></i>
                            Book Cover Image <span class="text-gray-400 ml-1 font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <input type="file" name="image" id="imageUpload" accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(event)">
                            <label for="imageUpload" 
                                   class="input-focus flex flex-col items-center justify-center w-full h-48 rounded-xl border-2 border-dashed border-gray-300 cursor-pointer hover:border-indigo-500 transition-all duration-300 bg-gradient-to-br from-gray-50 to-indigo-50 @error('image') border-red-500 @enderror">
                                <div id="imagePreviewContainer" class="hidden absolute inset-0 p-2">
                                    <img id="imagePreview" src="" alt="Preview" class="w-full h-full object-contain rounded-xl">
                                    <button type="button" onclick="removeImage(event)" 
                                            class="absolute top-4 right-4 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-all shadow-lg">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div id="uploadPrompt" class="text-center p-6">
                                    <i class="fas fa-cloud-upload-alt text-5xl text-indigo-400 mb-3"></i>
                                    <p class="text-sm font-semibold text-gray-700 mb-1">Click to upload book cover</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-2 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-camera text-indigo-400 mr-1"></i>
                            Upload a picture of the book cover to help us identify it faster
                        </p>
                    </div>

                    <!-- Priority -->
                    <div class="mb-6 animate-fade-in-up animate-delay-500">
                        <label class="block mb-4 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-flag text-orange-500 mr-2"></i>
                            Priority Level <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="priority-card flex items-center p-4 rounded-xl cursor-pointer relative overflow-hidden">
                                <input type="radio" name="priority" value="HIGH" {{ old('priority') == 'HIGH' ? 'checked' : '' }}
                                       class="mr-4 text-pink-600 w-5 h-5 cursor-pointer">
                                <div class="absolute inset-0 transition-all duration-300"></div>
                                <div class="flex items-center gap-3 relative z-10">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-2xl shadow-lg">
                                        🔥
                                    </div>
                                    <div>
                                        <span class="font-bold text-red-600 text-base">High Priority</span>
                                        <p class="text-xs text-gray-600 mt-1">I really need this book urgently!</p>
                                    </div>
                                </div>
                            </label>

                            <label class="priority-card flex items-center p-4 rounded-xl cursor-pointer relative overflow-hidden">
                                <input type="radio" name="priority" value="MEDIUM" {{ old('priority', 'MEDIUM') == 'MEDIUM' ? 'checked' : '' }}
                                       class="mr-4 text-pink-600 w-5 h-5 cursor-pointer">
                                <div class="absolute inset-0 transition-all duration-300"></div>
                                <div class="flex items-center gap-3 relative z-10">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-2xl shadow-lg">
                                        ⭐
                                    </div>
                                    <div>
                                        <span class="font-bold text-yellow-600 text-base">Medium Priority</span>
                                        <p class="text-xs text-gray-600 mt-1">I'm interested in this book</p>
                                    </div>
                                </div>
                            </label>

                            <label class="priority-card flex items-center p-4 rounded-xl cursor-pointer relative overflow-hidden">
                                <input type="radio" name="priority" value="LOW" {{ old('priority') == 'LOW' ? 'checked' : '' }}
                                       class="mr-4 text-pink-600 w-5 h-5 cursor-pointer">
                                <div class="absolute inset-0 transition-all duration-300"></div>
                                <div class="flex items-center gap-3 relative z-10">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-2xl shadow-lg">
                                        ✨
                                    </div>
                                    <div>
                                        <span class="font-bold text-green-600 text-base">Low Priority</span>
                                        <p class="text-xs text-gray-600 mt-1">Nice to have, not urgent</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('priority')
                            <p class="mt-2 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Max Price Budget (Optional) -->
                    <div class="mb-8 animate-fade-in-up animate-delay-500">
                        <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-dollar-sign text-green-500 mr-2"></i>
                            Maximum Price Budget <span class="text-gray-400 ml-1 font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <span class="text-gray-500 font-bold text-lg">$</span>
                            </div>
                            <input type="number" name="max_price" value="{{ old('max_price') }}" step="0.01" min="0"
                                   placeholder="50.00"
                                   class="input-focus block w-full rounded-xl border-2 border-gray-200 pl-10 pr-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-green-500 @error('max_price') border-red-500 @enderror">
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-wallet text-gray-300"></i>
                            </div>
                        </div>
                        @error('max_price')
                            <p class="mt-2 text-xs text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                            Set your budget to help us find books within your price range
                        </p>
                    </div>

                    <!-- Info Box -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-purple-50 border-2 border-blue-200 rounded-2xl p-6 mb-8 animate-fade-in-up animate-delay-500">
                        <div class="absolute top-0 right-0 text-6xl opacity-5">
                            💡
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="font-bold text-blue-900 mb-2 text-base">How it works</h6>
                                <p class="text-sm text-blue-800 leading-relaxed">
                                    Once you submit this request, our team will search for the book. 
                                    We'll notify you when we find it and keep you updated on the status every step of the way!
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-4 animate-fade-in-up animate-delay-500">
                        <button type="submit"
                                class="flex-1 px-8 py-4 font-bold text-center text-white uppercase rounded-xl text-sm shadow-xl hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 relative overflow-hidden group"
                                style="background:linear-gradient(135deg,#d946ef,#ec4899,#f97316)">
                            <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                            <span class="relative z-10 flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i> Submit Request
                            </span>
                        </button>
                        <a href="{{ route('wishlist.index') }}"
                           class="px-8 py-4 font-bold text-center text-gray-700 bg-gradient-to-r from-gray-100 to-gray-200 uppercase rounded-xl text-sm shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center border-2 border-gray-300">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Tip Section -->
        <div class="mt-8 text-center animate-fade-in-up animate-delay-500">
            <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                <i class="fas fa-users text-purple-500"></i>
                <span>Other users can vote on your request to show interest!</span>
                <i class="fas fa-thumbs-up text-blue-500"></i>
            </p>
        </div>
    </div>

    <script>
        // Image preview function
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').classList.remove('hidden');
                    document.getElementById('uploadPrompt').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove image function
        function removeImage(event) {
            event.preventDefault();
            event.stopPropagation();
            document.getElementById('imageUpload').value = '';
            document.getElementById('imagePreview').src = '';
            document.getElementById('imagePreviewContainer').classList.add('hidden');
            document.getElementById('uploadPrompt').classList.remove('hidden');
        }

        // Add smooth scroll to first error if validation fails
        document.addEventListener('DOMContentLoaded', function() {
            const firstError = document.querySelector('.text-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Add character counter for description
            const description = document.querySelector('textarea[name="description"]');
            if (description) {
                const maxLength = 500;
                const counterDiv = document.createElement('div');
                counterDiv.className = 'text-xs text-right mt-1 text-gray-400';
                counterDiv.textContent = `0 / ${maxLength} characters`;
                description.parentNode.appendChild(counterDiv);

                description.addEventListener('input', function() {
                    const length = this.value.length;
                    counterDiv.textContent = `${length} / ${maxLength} characters`;
                    if (length > maxLength * 0.9) {
                        counterDiv.classList.add('text-orange-500');
                    } else {
                        counterDiv.classList.remove('text-orange-500');
                    }
                });
            }

            // Add loading state to submit button
            const form = document.getElementById('wishlistForm');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            });

            // Add interactive feedback to inputs
            const inputs = document.querySelectorAll('input:not([type="file"]), textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-purple-200');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-purple-200');
                });
            });

            // Drag and drop for image upload
            const imageLabel = document.querySelector('label[for="imageUpload"]');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                imageLabel.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                imageLabel.addEventListener(eventName, function() {
                    this.classList.add('border-indigo-500', 'bg-indigo-100');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                imageLabel.addEventListener(eventName, function() {
                    this.classList.remove('border-indigo-500', 'bg-indigo-100');
                }, false);
            });

            imageLabel.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                document.getElementById('imageUpload').files = files;
                
                if (files.length > 0) {
                    const event = { target: { files: files } };
                    previewImage(event);
                }
            }, false);
        });
    </script>
</x-layouts.wishlist> 