<li class="nav-item dropdown" id="megaMenu">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
        Layanan
    </a>

    <div class="dropdown-menu p-4" style="min-width: 700px;">
        <div class="row">

            @foreach($MenuCategories->where('parent_id', 0) as $parent)
                <div class="col-3 mb-3">
                    <h6 class="fw-bold">{{ $parent->category_name }}</h6>

                    @php
                        $subcategories = $MenuCategories->where('parent_id', $parent->id);
                    @endphp

                    @forelse($subcategories as $child)
                        <a class="dropdown-item" href="{{ url('kategori/' . $child->url) }}">
                            {{ $child->category_name }}
                        </a>
                    @empty
                        <span class="text-muted small">Belum ada data</span>
                    @endforelse
                </div>
            @endforeach

        </div>
    </div>
</li>
