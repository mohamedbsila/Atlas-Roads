<x-layouts.base>
    @push('styles')
        <style>
            .community-card{transition:box-shadow .2s,transform .2s}
            .community-card:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.08)}
        </style>
    @endpush

    <div class="max-w-6xl mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">Communities</h1>
                <p class="text-slate-500">Browse and join communities</p>
            </div>
            <div class="flex items-center gap-3">
                <input id="community-search" type="text" placeholder="Search communities..." class="px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button id="community-search-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Search</button>
            </div>
        </div>

        <div id="communities-loading" class="text-slate-500 mb-4 hidden">Loading…</div>
        <div id="communities-error" class="text-red-600 mb-4 hidden"></div>

        <div id="communities-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

        <div class="flex items-center justify-between mt-8">
            <button id="btn-prev" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg disabled:opacity-50" disabled>Previous</button>
            <div id="page-indicator" class="text-slate-500"></div>
            <button id="btn-next" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg disabled:opacity-50" disabled>Next</button>
        </div>
    </div>

    @push('scripts')
        <script>
            (function(){
                const listEl = document.getElementById('communities-list');
                const loadingEl = document.getElementById('communities-loading');
                const errorEl = document.getElementById('communities-error');
                const searchInput = document.getElementById('community-search');
                const searchBtn = document.getElementById('community-search-btn');
                const prevBtn = document.getElementById('btn-prev');
                const nextBtn = document.getElementById('btn-next');
                const pageIndicator = document.getElementById('page-indicator');

                const state = {page: 1, search: ''};

                function setLoading(isLoading){
                    loadingEl.classList.toggle('hidden', !isLoading);
                }

                function setError(message){
                    if(!message){
                        errorEl.classList.add('hidden');
                        errorEl.textContent = '';
                        return;
                    }
                    errorEl.textContent = message;
                    errorEl.classList.remove('hidden');
                }

                function renderCommunities(items){
                    listEl.innerHTML = '';
                    if(!items || items.length === 0){
                        const empty = document.createElement('div');
                        empty.className = 'col-span-3 text-center text-slate-500';
                        empty.textContent = 'No communities found';
                        listEl.appendChild(empty);
                        return;
                    }
                    for (const community of items){
                        const card = document.createElement('div');
                        card.className = 'community-card bg-white rounded-xl border border-slate-200 p-5 flex flex-col';

                        const title = document.createElement('h3');
                        title.className = 'text-lg font-semibold text-slate-800 mb-1';
                        title.textContent = community.name ?? 'Community';

                        const desc = document.createElement('p');
                        desc.className = 'text-sm text-slate-600 mb-4 flex-1';
                        desc.textContent = community.description ?? '';

                        const actions = document.createElement('div');
                        actions.className = 'flex gap-2';

                        const joinBtn = document.createElement('button');
                        joinBtn.className = 'px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700';
                        joinBtn.textContent = 'Join';
                        joinBtn.addEventListener('click', async () => {
                            try {
                                await window.CommunityAPI.joinCommunity(community.id);
                                showToast('Joined community');
                            } catch (e) {
                                setError('Failed to join community');
                            }
                        });

                        const leaveBtn = document.createElement('button');
                        leaveBtn.className = 'px-3 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300';
                        leaveBtn.textContent = 'Leave';
                        leaveBtn.addEventListener('click', async () => {
                            try {
                                await window.CommunityAPI.leaveCommunity(community.id);
                                showToast('Left community');
                            } catch (e) {
                                setError('Failed to leave community');
                            }
                        });

                        actions.appendChild(joinBtn);
                        actions.appendChild(leaveBtn);

                        card.appendChild(title);
                        card.appendChild(desc);
                        card.appendChild(actions);
                        listEl.appendChild(card);
                    }
                }

                function updatePagination(meta){
                    const hasPrev = !!meta.prev_page_url;
                    const hasNext = !!meta.next_page_url;
                    prevBtn.disabled = !hasPrev;
                    nextBtn.disabled = !hasNext;
                    pageIndicator.textContent = `Page ${meta.current_page} of ${meta.last_page}`;
                    prevBtn.onclick = () => { if (hasPrev) { state.page = Math.max(1, (meta.current_page || 1) - 1); load(); } };
                    nextBtn.onclick = () => { if (hasNext) { state.page = (meta.current_page || 1) + 1; load(); } };
                }

                async function load(){
                    setError('');
                    setLoading(true);
                    try {
                        const data = await window.CommunityAPI.listCommunities({ page: state.page, search: state.search });
                        renderCommunities(data.data || []);
                        updatePagination({
                            current_page: data.current_page,
                            last_page: data.last_page,
                            prev_page_url: data.prev_page_url,
                            next_page_url: data.next_page_url
                        });
                    } catch (e) {
                        setError('Failed to load communities');
                    } finally {
                        setLoading(false);
                    }
                }

                searchBtn.addEventListener('click', () => {
                    state.search = searchInput.value.trim();
                    state.page = 1;
                    load();
                });
                searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        state.search = searchInput.value.trim();
                        state.page = 1;
                        load();
                    }
                });

                load();
            })();
        </script>
    @endpush
</x-layouts.base>
