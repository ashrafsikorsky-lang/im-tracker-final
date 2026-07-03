@extends('layouts.app')

@section('title', 'Dokumentasi Sistem')

@section('content')
    <div id="docsContainer" style="padding: 20px; max-width: 900px; margin: 0 auto;">
        
        <!-- Search Box -->
        <input type="text" id="searchBox" placeholder="Cari dokumentasi..." style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px;">

        @forelse ($docs as $doc)
            <div class="doc-section" data-search="{{ strtolower($doc->class_name . ' ' . $doc->documentation) }}" style="background: white; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
                <div class="doc-header" onclick="toggleDoc(this)" style="padding: 15px; background: #f8fafc; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0;">
                    <div class="doc-title" style="font-weight: bold; color: #1e293b;">
                        <span class="doc-badge" style="background: #3b82f6; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8em; margin-right: 10px;">
                            @if (Str::contains(strtolower($doc->class_name), 'controller'))
                                CONTROLLER
                            @else
                                CLASS
                            @endif
                        </span>
                        {{ $doc->class_name }}
                    </div>
                    <span class="collapse-icon">▼</span>
                </div>
                <div class="doc-content" style="display: none; padding: 20px;">
                    <div class="doc-body">
                        <div class="doc-meta" style="background: #f1f5f9; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                            <div class="meta-title" style="font-weight: bold; margin-bottom: 10px;">📋 Maklumat Kelas</div>
                            <div class="meta-content" style="font-size: 0.9em; line-height: 1.6;">
                                <strong>Nama Kelas:</strong> {{ $doc->class_name }}<br>
                                <strong>Laluan Sumber:</strong> {{ $doc->source_path }}<br>
                                <strong>Dijana pada:</strong> {{ $doc->updated_at ? $doc->updated_at->format('d M Y, H:i') : 'N/A' }}
                            </div>
                        </div>

                        <div class="markdown-body" style="line-height: 1.6;">
                            {!! $doc->documentation_html !!}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-results" style="text-align: center; padding: 40px;">
                <h3>🚫 Tiada dokumentasi dijumpai</h3>
                <p>Sila jalankan perintah <code class="command">php artisan generate:docs</code> untuk menjana dokumentasi.</p>
            </div>
        @endforelse
    </div>

    <div id="noResults" class="no-results" style="display: none; text-align: center; padding: 40px;">
        <h3>🔍 Tiada hasil carian dijumpai</h3>
        <p>Cuba cari dengan kata kunci yang berbeza.</p>
    </div>

    <script>
        function toggleDoc(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.collapse-icon');
            const isCurrentlyVisible = content.style.display === 'block';

            // Close all others
            document.querySelectorAll('.doc-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.collapse-icon').forEach(el => el.textContent = '▼');

            // Toggle current
            if (!isCurrentlyVisible) {
                content.style.display = 'block';
                icon.textContent = '▲';
            }
        }

        document.getElementById('searchBox').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const docSections = document.querySelectorAll('.doc-section');
            const noResults = document.getElementById('noResults');
            let hasResults = false;

            docSections.forEach(section => {
                const searchData = section.getAttribute('data-search');
                
                if (searchData.includes(searchTerm) || searchTerm === '') {
                    section.style.display = 'block';
                    hasResults = true;
                } else {
                    section.style.display = 'none';
                }
            });

            noResults.style.display = hasResults ? 'none' : 'block';
        });
    </script>
@endsection