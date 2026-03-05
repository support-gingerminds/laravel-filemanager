<ul class="nav nav-pills flex-column">
    @foreach($root_folders as $root_folder)
        <li class="nav-item">
            @if(isset($root_folder->children) && count($root_folder->children) > 0)
                {{ view('laravel-filemanager::components.toggle-icon', ['type' => 'move', 'index' => $loop->index, 'class' => 'down'])}}
            @endif
                <a class="nav-link" href="#" data-type="0" data-url="{{$root_folder->url}}" onclick="moveToNewFolder(this)">
                <i class="fa fa-folder fa-fw"></i> {{ $root_folder->name }}
                <input type="hidden" id="goToFolder" name="goToFolder" value="{{ $root_folder->url }}">
                <div id="items">
                    @foreach($items as $i)
                        <input type="hidden" id="{{ $i }}" name="items[]" value="{{ $i }}">
                    @endforeach
                </div>
            </a>
        </li>
        @if(isset($root_folder->children) && count($root_folder->children) > 0)
            <ul id="root-move-{{ $loop->index }}" class="nav nav-pills flex-column subdir">
                {{ $helper->renderSubdirectories('move', $root_folder->children, $items, 1) }}
            </ul>
        @endif
    @endforeach
</ul>

<script nonce="4qtr19v5uce5kmb5fj">
  function moveToNewFolder(e) {
    const url = e.getAttribute('data-url');
    $("#notify").modal('hide');
    var items = [];
    $("#items").find("input").each(function() { items.push(this.id); });
    performLfmRequest('domove', {
      items: items,
      goToFolder: url
    }).done(refreshFoldersAndItems);
  }
</script>
