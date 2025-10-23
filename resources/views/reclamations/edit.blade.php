<<<<<<< HEAD
<x-layouts.reclamations>
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
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    .animate-slide-in-left { animation: slideInLeft 0.5s ease-out forwards; }
    
    .animate-delay-100 { animation-delay: 0.1s; opacity: 0; }
    .animate-delay-200 { animation-delay: 0.2s; opacity: 0; }
    .animate-delay-300 { animation-delay: 0.3s; opacity: 0; }
    .animate-delay-400 { animation-delay: 0.4s; opacity: 0; }
    .animate-delay-500 { animation-delay: 0.5s; opacity: 0; }

    .input-focus {
        transition: all 0.3s ease;
    }

    .input-focus:focus {
        transform: scale(1.02);
        box-shadow: 0 0 0 3px rgba(217, 70, 239, 0.1);
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
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-6">
        <div>
            <h5 class="mb-0 font-bold text-2xl gradient-text">📝 Edit Reclamation</h5>
            <p class="text-sm text-slate-500 mt-2 flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-purple-500"></i>
                Update your reported issue
            </p>
        </div>
        <div>
            <a href="{{ route('reclamations.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg relative overflow-hidden group"
               style="background:linear-gradient(135deg,#06b6d4,#3b82f6)">
                <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                <span class="relative z-10 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 text-white rounded-xl flex items-center justify-between animate-fade-in-up" 
             style="background:linear-gradient(to right,#ef4444,#dc2626)">
            <div>
                <p class="font-bold flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> Please fix the following errors:
                </p>
                <ul class="mt-2 ml-6 list-disc">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Form Container -->
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-2xl rounded-3xl bg-clip-border overflow-hidden animate-fade-in-up">
        <!-- Decorative gradient header -->
        <div class="absolute top-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#d946ef,#ec4899,#f97316,#06b6d4,#3b82f6)"></div>
        
        <div class="border-b border-gray-200 p-8 relative">
            <div class="absolute right-8 top-8 floating-icon text-5xl opacity-10">
                ✏️
            </div>
            <h5 class="mb-2 font-bold text-3xl gradient-text">Edit Reclamation</h5>
            <p class="text-sm text-slate-500 mt-2 flex items-center">
                <i class="fas fa-edit mr-2 text-purple-500"></i>
                Update the details of your issue
            </p>
        </div>

        <div class="p-8">
            <form action="{{ route('reclamations.update', $reclamation) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-6 animate-fade-in-up">
                    <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                        <i class="fas fa-heading text-purple-500 mr-2"></i>
                        Title <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="titre" value="{{ old('titre', $reclamation->titre) }}" required
                               placeholder="Enter a descriptive title"
                               class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-purple-500 @error('titre') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <i class="fas fa-pencil-alt text-gray-300"></i>
                        </div>
                    </div>
                    @error('titre')
                        <p class="mt-2 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6 animate-fade-in-up animate-delay-100">
                    <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                        <i class="fas fa-comment-dots text-pink-500 mr-2"></i>
                        Description <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea name="description" rows="4" required
                              placeholder="Please provide a detailed description of your issue"
                              class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-pink-500 resize-none @error('description') border-red-500 @enderror">{{ old('description', $reclamation->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                        Provide details to help us resolve your issue faster
                    </p>
                </div>

                <!-- Category -->
                <div class="mb-6 animate-fade-in-up animate-delay-200">
                    <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        Category <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <select name="categorie" required
                                class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500 appearance-none bg-white @error('categorie') border-red-500 @enderror">
                            <option value="" {{ old('categorie', $reclamation->categorie) ? '' : 'selected' }}>Select a category</option>
                            <option value="technical" {{ old('categorie', $reclamation->categorie) == 'technical' ? 'selected' : '' }}>Technical Issue</option>
                            <option value="billing" {{ old('categorie', $reclamation->categorie) == 'billing' ? 'selected' : '' }}>Billing</option>
                            <option value="account" {{ old('categorie', $reclamation->categorie) == 'account' ? 'selected' : '' }}>Account</option>
                            <option value="feature" {{ old('categorie', $reclamation->categorie) == 'feature' ? 'selected' : '' }}>Feature Request</option>
                            <option value="other" {{ old('categorie', $reclamation->categorie) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-300"></i>
                        </div>
                    </div>
                    @error('categorie')
                        <p class="mt-2 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="mb-6 animate-fade-in-up animate-delay-300">
                    <label class="block mb-4 text-sm font-bold text-slate-700 flex items-center">
                        <i class="fas fa-flag text-orange-500 mr-2"></i>
                        Priority Level <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 rounded-xl cursor-pointer relative overflow-hidden border-2 border-gray-200 hover:border-purple-300 transition-all">
                            <input type="radio" name="priorite" value="haute" {{ old('priorite', $reclamation->priorite) == 'haute' ? 'checked' : '' }}
                                   class="mr-4 text-pink-600 w-5 h-5 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-xl text-white">
                                    🔥
                                </div>
                                <div>
                                    <span class="font-bold text-red-600">High Priority</span>
                                    <p class="text-xs text-gray-600 mt-1">Urgent issue requiring immediate attention</p>
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 rounded-xl cursor-pointer relative overflow-hidden border-2 border-gray-200 hover:border-purple-300 transition-all">
                            <input type="radio" name="priorite" value="moyenne" {{ old('priorite', $reclamation->priorite) == 'moyenne' ? 'checked' : '' }}
                                   class="mr-4 text-pink-600 w-5 h-5 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-xl text-white">
                                    ⭐
                                </div>
                                <div>
                                    <span class="font-bold text-yellow-600">Medium Priority</span>
                                    <p class="text-xs text-gray-600 mt-1">Standard issue, needs timely resolution</p>
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 rounded-xl cursor-pointer relative overflow-hidden border-2 border-gray-200 hover:border-purple-300 transition-all">
                            <input type="radio" name="priorite" value="basse" {{ old('priorite', $reclamation->priorite) == 'basse' ? 'checked' : '' }}
                                   class="mr-4 text-pink-600 w-5 h-5 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-xl text-white">
                                    ✨
                                </div>
                                <div>
                                    <span class="font-bold text-green-600">Low Priority</span>
                                    <p class="text-xs text-gray-600 mt-1">Non-urgent issue, can be addressed later</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('priorite')
                        <p class="mt-2 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6 animate-fade-in-up animate-delay-400">
                    <label class="block mb-3 text-sm font-bold text-slate-700 flex items-center">
                        <i class="fas fa-tasks text-indigo-500 mr-2"></i>
                        Status <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <select name="statut" required
                                class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-indigo-500 appearance-none bg-white @error('statut') border-red-500 @enderror">
                            <option value="en_attente" {{ old('statut', $reclamation->statut) == 'en_attente' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="en_cours" {{ old('statut', $reclamation->statut) == 'en_cours' ? 'selected' : '' }}>🔄 In Progress</option>
                            <option value="resolue" {{ old('statut', $reclamation->statut) == 'resolue' ? 'selected' : '' }}>✅ Resolved</option>
                            <option value="rejetee" {{ old('statut', $reclamation->statut) == 'rejetee' ? 'selected' : '' }}>❌ Rejected</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-300"></i>
                        </div>
                    </div>
                    @error('statut')
                        <p class="mt-2 text-xs text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 mt-8">
                    <button type="submit"
                            class="flex-1 px-8 py-4 font-bold text-center text-white uppercase rounded-xl text-sm shadow-xl hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 relative overflow-hidden group"
                            style="background:linear-gradient(135deg,#d946ef,#ec4899,#f97316)">
                        <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i> Update Reclamation
                        </span>
                    </button>
                    <a href="{{ route('reclamations.index') }}"
                       class="px-8 py-4 font-bold text-center text-gray-700 bg-gradient-to-r from-gray-100 to-gray-200 uppercase rounded-xl text-sm shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center border-2 border-gray-300">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-layouts.reclamations>
=======
<x-layouts.app>
    <div>
        <div class="mb-6">
            <a href="{{ route('reclamations.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-size-xs font-bold text-slate-400 hover:text-slate-700">
                <i class="ni ni-bold-left mr-1"></i> Retour à la liste
            </a>
            <h5 class="mb-0 font-bold">Modifier Réclamation</h5>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="w-full lg:w-8/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reclamations.update', $reclamation) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Titre</label>
                        <input type="text" name="titre" value="{{ $reclamation->titre }}" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Description</label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded" required>{{ $reclamation->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Catégorie</label>
                        <input type="text" name="categorie" value="{{ $reclamation->categorie }}" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Priorité</label>
                        <select name="priorite" class="w-full px-3 py-2 border rounded" required>
                            <option value="basse" {{ $reclamation->priorite=='basse'?'selected':'' }}>Basse</option>
                            <option value="moyenne" {{ $reclamation->priorite=='moyenne'?'selected':'' }}>Moyenne</option>
                            <option value="haute" {{ $reclamation->priorite=='haute'?'selected':'' }}>Haute</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Statut</label>
                        <select name="statut" class="w-full px-3 py-2 border rounded" required>
                            <option value="en_attente" {{ $reclamation->statut=='en_attente'?'selected':'' }}>En attente</option>
                            <option value="en_cours" {{ $reclamation->statut=='en_cours'?'selected':'' }}>En cours</option>
                            <option value="resolue" {{ $reclamation->statut=='resolue'?'selected':'' }}>Résolue</option>
                        </select>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('reclamations.index') }}" 
                           class="inline-block px-6 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-transparent border border-slate-700 rounded-lg cursor-pointer hover:bg-slate-700 hover:text-white text-size-xs">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105"
                                style="background:linear-gradient(to right,#d946ef,#ec4899)">
                            Mettre à jour
                        </button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
>>>>>>> origin/complet
