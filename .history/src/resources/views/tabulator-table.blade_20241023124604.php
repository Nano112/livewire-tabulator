<?php
// Save as setup-views.php in your package root

$viewsPath = __DIR__ . '/src/resources/views';
$viewFile = $viewsPath . '/tabulator-table.blade.php';

// Ensure directories exist
if (!is_dir($viewsPath)) {
    mkdir($viewsPath, 0755, true);
    echo "Created views directory: {$viewsPath}\n";
}

// Create view file if it doesn't exist
if (!file_exists($viewFile)) {
    $viewContent = <<<'BLADE'
<div>
    @assets
        @once
            <link href="{{ config('tabulator.cdn.css', 'https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css') }}" rel="stylesheet">
            <script src="{{ config('tabulator.cdn.js', 'https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js') }}"></script>
        @endonce
    @endassets

    <div wire:ignore x-data="tabulatorComponent()">
        <div x-ref="table"></div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Alpine.data('tabulatorComponent', () => ({
                table: null,
                init() {
                    this.table = new Tabulator(this.$refs.table, {
                        data: @json($data),
                        columns: @json($columns),
                        ...@json($options)
                    });

                    // Set up event listeners
                    @foreach($events as $event => $method)
                        this.table.on('{{ $event }}', (...args) => {
                            @this.call('{{ $method }}', args);
                        });
                    @endforeach
                }
            }));
        });
    </script>
</div>
BLADE;
    
    file_put_contents($viewFile, $viewContent);
    echo "Created view file: {$viewFile}\n";
}

// Set permissions
chmod($viewsPath, 0755);
chmod($viewFile, 0644);

echo "Setup complete!\n";