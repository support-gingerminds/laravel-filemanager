@php
    function renderSubdirectories($directories, $items, $level = 1) {
        foreach ($directories as $directory) {
            $class = 'level-' . $level;
            echo '<li class="nav-item sub-item ' . $class . '">';
            echo '<a class="nav-link" href="#" data-type="0" onclick="moveToNewFolder(`' . $directory->url . '`)">';
            echo '<i class="fa fa-folder fa-fw"></i> ' . $directory->name;
            echo '<input type="hidden" id="goToFolder" name="goToFolder" value="' . $directory->url . '">';
            echo '<div id="items">';
            foreach ($items as $i) {
                echo '<input type="hidden" id="' . $i . '" name="items[]" value="' . $i . '">';
            }
            echo '</div>';
            echo '</a>';
            if (isset($directory->children)) {
                echo '<ul class="nav nav-pills flex-column">';
                renderSubdirectories($directory->children, $items, $level + 1);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
@endphp

<ul class="nav nav-pills flex-column">
    @foreach($root_folders as $root_folder)
        <li class="nav-item">
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
        @if(isset($root_folder->children))
            <ul class="nav nav-pills flex-column">
                @php renderSubdirectories($root_folder->children, $items, 1); @endphp
            </ul>
        @endif
    @endforeach
</ul>

<script>
  function moveToNewFolder($folder) {
    $("#notify").modal('hide');
    var items =[];
    $("#items").find("input").each(function() {items.push(this.id)});
    performLfmRequest('domove', {
      items: items,
      goToFolder: $folder
    }).done(refreshFoldersAndItems);
  }
</script>
