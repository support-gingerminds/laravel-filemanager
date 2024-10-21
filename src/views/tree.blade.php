<ul class="nav nav-pills flex-column">
    @foreach($root_folders as $root_folder)
        <li class="nav-item">
            @if(isset($root_folder->children) && count($root_folder->children) > 0)
                {{ view('laravel-filemanager::components.toggle-icon', ['type' => 'tree', 'index' => $loop->index, 'class' => 'down'])}}
            @endif
            <a class="nav-link" href="#" data-type="0" data-path="{{ $root_folder->url }}">
                <i class="fa fa-folder fa-fw"></i> {{ $root_folder->name }}
            </a>
            @if(isset($root_folder->children) && count($root_folder->children) > 0)
                <ul id="root-tree-{{ $loop->index }}" class="nav nav-pills flex-column subdir">
                    {{ $helper->renderSubdirectories('tree', $root_folder->children, [], 1) }}
                </ul>
            @endif
        </li>
    @endforeach
</ul>

<script nonce="4qtr19v5uce5kmb5fj">
  function toggleSubdirectories(id, icon) {
    if (icon.classList.contains('down')) {
      icon.classList.remove('down');
    } else {
      icon.classList.add('down');
    }

    var element = document.getElementById(id);
    if (element.classList.contains('hidden')) {
      element.classList.remove('hidden');
    } else {
      element.classList.add('hidden');
    }
  }
</script>
