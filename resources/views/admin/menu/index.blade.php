@extends('admin.layouts.app')

@section('title', 'Kelola Menu Website')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white mb-0">Manajemen Menu Website</h5>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ url('/') }}" target="_blank" class="btn btn-warning float-end">Live Preview</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i> 
                        <strong>Informasi:</strong> Menu <strong>Beranda</strong> dan <strong>Kontak</strong> adalah menu statis yang tidak dapat diubah melalui sistem manajemen menu ini.
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('menu.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Tambah Menu
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="menuTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Judul Menu</th>
                                    <th width="25%">URL</th>
                                    <th width="12%">Tipe</th>
                                    <th width="8%">Urutan</th>
                                    <th width="8%">Status</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-menu">
                                @forelse($menus as $index => $menu)
                                    <tr data-id="{{ $menu->id }}" data-position="{{ $menu->position }}" class="menu-row parent-menu">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $menu->title }}</strong>
                                            @if($menu->icon)
                                                <i class="{{ $menu->icon }} ms-2"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $menu->full_url ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-type" 
                                                       type="checkbox" 
                                                       data-id="{{ $menu->id }}"
                                                       data-has-page="{{ $menu->page ? 'true' : 'false' }}"
                                                       data-has-children="{{ $menu->children->count() > 0 ? 'true' : 'false' }}"
                                                       {{ $menu->children->count() > 0 ? 'checked' : '' }}
                                                       title="Toggle: Parent Only / Parent with Sub">
                                                <label class="form-check-label">
                                                    <small id="type-label-{{ $menu->id }}">
                                                        {{ $menu->children->count() > 0 ? 'Parent with Sub' : 'Parent Only' }}
                                                    </small>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $menu->order }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-status" 
                                                       type="checkbox" 
                                                       data-id="{{ $menu->id }}"
                                                       {{ $menu->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('menu.edit', $menu->id) }}" 
                                                   class="btn btn-sm btn-warning"
                                                   title="Edit Menu">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                
                                                <span class="action-buttons-{{ $menu->id }}">
                                                    @if($menu->children->count() > 0)
                                                        {{-- Menu has children: show "Add Submenu" button with modal trigger --}}
                                                        <button type="button" 
                                                                class="btn btn-sm btn-info add-submenu-btn" 
                                                                data-parent-id="{{ $menu->id }}"
                                                                title="Tambah Submenu">
                                                            <i class="ti ti-plus"></i>
                                                        </button>
                                                    @else
                                                        {{-- Menu has no children: show page button (Edit or Create) --}}
                                                        @if($menu->page)
                                                            <a href="{{ route('pages.edit', $menu->page->id) }}" 
                                                               class="btn btn-sm btn-success"
                                                               title="Edit Halaman">
                                                                <i class="ti ti-file-text"></i>
                                                            </a>
                                                        @else
                                                            <button type="button"
                                                                    class="btn btn-sm btn-primary create-page-btn"
                                                                    data-menu-id="{{ $menu->id }}"
                                                                    data-menu-title="{{ $menu->title }}"
                                                                    data-menu-slug="{{ $menu->slug }}"
                                                                    title="Buat Halaman">
                                                                <i class="ti ti-file-plus"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </span>
                                                
                                                <form action="{{ route('menu.destroy', $menu->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    @if($menu->children->count() > 0)
                                        @foreach($menu->children as $child)
                                            <tr data-id="{{ $child->id }}" data-position="{{ $child->position }}" class="menu-row child-menu bg-light">
                                                <td class="border-start border-primary border-3"></td>
                                                <td class="ps-4">
                                                    <i class="ti ti-corner-down-right text-primary"></i>
                                                    <span class="ms-1">{{ $child->title }}</span>
                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }} ms-2"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $child->full_url ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted">Submenu</small>
                                                </td>
                                                <td class="text-center">
                                                    <small class="text-muted">{{ $menu->order }}.{{ $loop->iteration }}</small>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status" 
                                                               type="checkbox" 
                                                               data-id="{{ $child->id }}"
                                                               {{ $child->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('menu.edit', $child->id) }}" 
                                                           class="btn btn-sm btn-warning"
                                                           title="Edit Menu">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        
                                                        {{-- Always show page button: Edit if exists, Create if not --}}
                                                        @if($child->page)
                                                            <a href="{{ route('pages.edit', $child->page->id) }}" 
                                                               class="btn btn-sm btn-success"
                                                               title="Edit Halaman">
                                                                <i class="ti ti-file-text"></i>
                                                            </a>
                                                        @else
                                                            <button type="button"
                                                                    class="btn btn-sm btn-primary create-page-btn"
                                                                    data-menu-id="{{ $child->id }}"
                                                                    data-menu-title="{{ $child->title }}"
                                                                    data-menu-slug="{{ $child->slug }}"
                                                                    title="Buat Halaman">
                                                                <i class="ti ti-file-plus"></i>
                                                            </button>
                                                        @endif
                                                        
                                                        <form action="{{ route('menu.destroy', $child->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada menu. Silakan tambah menu baru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Submenu -->
<div class="modal fade" id="addSubmenuModal" tabindex="-1" aria-labelledby="addSubmenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="addSubmenuModalLabel">Tambah Submenu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="submenuForm" action="{{ route('menu.store') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" id="parent_id">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="submenu_title" class="form-label">Judul Menu <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="submenu_title" 
                               name="title" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="submenu_slug" class="form-label">Slug</label>
                        <input type="text" 
                               class="form-control" 
                               id="submenu_slug" 
                               name="slug" 
                               readonly>
                        <small class="text-muted">Slug akan dibuat otomatis dari judul</small>
                    </div>

                    <div class="mb-3">
                        <label for="submenu_type" class="form-label">Tipe Menu <span class="text-danger">*</span></label>
                        <select class="form-select" id="submenu_type" name="type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="internal" selected>Internal Link</option>
                            <option value="external">External Link</option>
                            <option value="custom">Custom URL</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="submenu_url" class="form-label">URL</label>
                        <input type="text" 
                               class="form-control" 
                               id="submenu_url" 
                               name="url"
                               placeholder="Contoh: /berita atau https://example.com">
                    </div>

                    <div class="mb-3">
                        <label for="submenu_position" class="form-label">Posisi <span class="text-danger">*</span></label>
                        <select class="form-select" id="submenu_position" name="position" required>
                            <option value="header" selected>Header</option>
                            <option value="footer">Footer</option>
                            <option value="sidebar">Sidebar</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="submenu_order" class="form-label">Urutan</label>
                        <input type="number" 
                               class="form-control" 
                               id="submenu_order" 
                               name="order" 
                               value="0" 
                               min="0">
                    </div>

                    <input type="hidden" name="target" value="_self">
                    <input type="hidden" name="is_active" value="1">
                    <input type="hidden" name="create_page" value="1">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Submenu</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Custom Table Styling */
#menuTable {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    background-color: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    border: 2px solid #333 !important;
    border-radius: 10px !important;
    overflow: hidden !important;
}

#menuTable thead {
    background-color: #4a90e2 !important;
}

#menuTable thead th {
    background-color: #4a90e2 !important;
    color: white !important;
    padding: 12px 15px !important;
    text-align: left !important;
    font-weight: 600 !important;
    font-size: 13px !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 2px solid #3a7bc8 !important;
    vertical-align: middle !important;
}

#menuTable thead th:first-child {
    border-top-left-radius: 8px !important;
}

#menuTable thead th:last-child {
    border-top-right-radius: 8px !important;
}

#menuTable tbody tr {
    border: 2px solid #999 !important;
    transition: background-color 0.2s ease;
}

#menuTable tbody tr:nth-child(odd) {
    background-color: #f9f9f9 !important;
}

#menuTable tbody tr:nth-child(even) {
    background-color: #ffffff !important;
}

#menuTable tbody tr:hover {
    background-color: #f0f8ff !important;
}

#menuTable tbody tr.child-menu {
    background-color: #e8f4f8 !important;
}

#menuTable tbody tr.child-menu:hover {
    background-color: #d0e9f5 !important;
}

#menuTable tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px !important;
}

#menuTable tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px !important;
}

#menuTable td {
    padding: 10px 15px !important;
    font-size: 14px !important;
    color: #333 !important;
    vertical-align: middle !important;
    border: 2px solid #999 !important;
}

#menuTable th {
    text-align: left !important;
    vertical-align: middle !important;
}

.table-responsive {
    border-radius: 10px !important;
    overflow: hidden !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').change(function() {
        const menuId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        const checkbox = $(this);
        
        $.ajax({
            url: '/admin/menu/' + menuId + '/toggle',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    console.log(response.message);
                }
            },
            error: function() {
                // Revert checkbox on error
                checkbox.prop('checked', !isChecked);
                alert('Gagal mengubah status menu');
            }
        });
    });

    // Create Page Button
    $('.create-page-btn').click(function() {
        const menuId = $(this).data('menu-id');
        const menuTitle = $(this).data('menu-title');
        const menuSlug = $(this).data('menu-slug');
        const button = $(this);
        
        if(confirm('Buat halaman baru untuk menu "' + menuTitle + '"?')) {
            button.prop('disabled', true).html('<i class="ti ti-loader"></i>');
            
            $.ajax({
                url: '/admin/pages/create-from-menu',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    menu_id: menuId,
                    title: menuTitle,
                    slug: menuSlug
                },
                success: function(response) {
                    if(response.success) {
                        alert('Halaman berhasil dibuat!');
                        // Redirect to edit page
                        window.location.href = '/admin/pages/' + response.page_id + '/edit';
                    }
                },
                error: function() {
                    alert('Gagal membuat halaman!');
                    button.prop('disabled', false).html('<i class="ti ti-file-plus"></i>');
                }
            });
        }
    });

    // Toggle Type (Parent Only / Parent with Sub)
    $('.toggle-type').change(function() {
        const menuId = $(this).data('id');
        const hasPage = $(this).data('has-page');
        const hasChildren = $(this).data('has-children');
        const isChecked = $(this).is(':checked');
        const checkbox = $(this);
        
        if (isChecked) {
            // Switch to "Parent with Sub"
            if (hasPage === 'true') {
                if (!confirm('Menu ini memiliki halaman. Mengubah ke "Parent with Sub" akan menyembunyikan tombol edit halaman. Lanjutkan?')) {
                    checkbox.prop('checked', false);
                    return;
                }
            }
            
            $('#type-label-' + menuId).text('Parent with Sub');
            
            // Update action buttons to show "Add Submenu"
            $('.action-buttons-' + menuId).html(
                '<button type="button" class="btn btn-sm btn-info add-submenu-btn" data-parent-id="' + menuId + '" title="Tambah Submenu">' +
                '<i class="ti ti-plus"></i></button>'
            );
            
            // Re-bind modal trigger
            bindAddSubmenuButton();
        } else {
            // Switch to "Parent Only"
            // Only show confirmation if menu already has children
            if (hasChildren === 'true') {
                if (confirm('Menu ini memiliki submenu. Mengubah kembali ke "Parent Only"?')) {
                    $('#type-label-' + menuId).text('Parent Only');
                    location.reload();
                } else {
                    checkbox.prop('checked', true);
                }
            } else {
                // No children, just switch directly
                $('#type-label-' + menuId).text('Parent Only');
                location.reload();
            }
        }
    });

    function bindCreatePageButton() {
        $('.create-page-btn').off('click').on('click', function() {
            const menuId = $(this).data('menu-id');
            const button = $(this);
            
            if(confirm('Buat halaman baru untuk menu ini?')) {
                button.prop('disabled', true).html('<i class="ti ti-loader"></i>');
                
                $.ajax({
                    url: '/admin/pages/create-from-menu',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        menu_id: menuId
                    },
                    success: function(response) {
                        if(response.success) {
                            window.location.href = '/admin/pages/' + response.page_id + '/edit';
                        }
                    },
                    error: function() {
                        alert('Gagal membuat halaman!');
                        button.prop('disabled', false).html('<i class="ti ti-file-plus"></i>');
                    }
                });
            }
        });
    }

    // Handle Add Submenu Modal
    function bindAddSubmenuButton() {
        $('.add-submenu-btn').off('click').on('click', function() {
            const parentId = $(this).data('parent-id');
            $('#parent_id').val(parentId);
            $('#submenuForm')[0].reset();
            $('#parent_id').val(parentId); // Set again after reset
            $('#addSubmenuModal').modal('show');
        });
    }

    // Initialize add submenu button
    bindAddSubmenuButton();

    // Auto generate slug for submenu
    $('#submenu_title').on('keyup', function() {
        const title = $(this).val();
        $.ajax({
            url: '/admin/menu/slug',
            type: 'GET',
            data: { title: title },
            success: function(response) {
                $('#submenu_slug').val(response.slug);
            }
        });
    });
});
</script>
@endpush
@endsection
