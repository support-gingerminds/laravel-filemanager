<ul class="nav nav-pills flex-column">
    @foreach($root_folders as $root_folder)
        <li class="nav-item">
            @if(isset($root_folder->children) && count($root_folder->children) > 0)
                {{ view('laravel-filemanager::components.toggle-icon', ['type' => 'move', 'index' => $loop->index])}}
            @endif
            <a class="nav-link" href="#" data-type="0" onclick="moveToNewFolder(`{{$root_folder->url}}`)">
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
            <ul id="root-move-{{ $loop->index }}" class="nav nav-pills flex-column subdir hidden">
                {{ $helper->renderSubdirectories('move', $root_folder->children, $items, 1) }}
            </ul>
        @endif
    @endforeach
</ul>

<script>
  function moveToNewFolder($folder) {
    $("#notify").modal('hide');
    var items = [];
    $("#items").find("input").each(function() { items.push(this.id); });
    performLfmRequest('domove', {
      items: items,
      goToFolder: $folder
    }).done(refreshFoldersAndItems);
  }
</script>
