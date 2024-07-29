@php
  function renderSubdirectories($directories, $level = 1) {
      foreach ($directories as $directory) {
          $class = 'level-' . $level;
          $id = 'subdir-' . uniqid();
          echo '<li class="nav-item sub-item ' . $class . '">';
          if (isset($directory->children) && count($directory->children) > 0) {
              echo '<span class="toggle-icon" onclick="toggleSubdirectories(\'' . $id . '\', this)">
                  <svg class="arrow-right" width="17px" height="17px" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                      <path fill="#000000" fill-rule="evenodd" d="M5.29289,3.70711 C4.90237,3.31658 4.90237,2.68342 5.29289,2.29289 C5.68342,1.90237 6.31658,1.90237 6.70711,2.29289 L11.7071,7.29289 C12.0976,7.68342 12.0976,8.31658 11.7071,8.70711 L6.70711,13.7071 C6.31658,14.0976 5.68342,14.0976 5.29289,13.7071 C4.90237,13.3166 4.90237,12.6834 5.29289,12.2929 L9.58579,8 L5.29289,3.70711 Z"/>
                  </svg>
                  <svg class="arrow-down" width="17px" height="17px" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#000000" fill-rule="evenodd" d="M12.2929,5.292875 C12.6834,4.902375 13.3166,4.902375 13.7071,5.292875 C14.0976,5.683375 14.0976,6.316555 13.7071,6.707085 L8.70711,11.707085 C8.31658,12.097605 7.68342,12.097605 7.29289,11.707085 L2.29289,6.707085 C1.90237,6.316555 1.90237,5.683375 2.29289,5.292875 C2.68342,4.902375 3.31658,4.902375 3.70711,5.292875 L8,9.585765 L12.2929,5.292875 Z"/>
                  </svg>
              </span>';
          }
          echo '<a class="nav-link" href="#" data-type="0" data-path="' . $directory->url . '">';
          echo '<i class="fa fa-folder fa-fw"></i> ' . $directory->name;
          echo '</a>';
          if (isset($directory->children) && count($directory->children) > 0) {
              echo '<ul id="' . $id . '" class="nav nav-pills flex-column subdir hidden">';
              renderSubdirectories($directory->children, $level + 1);
              echo '</ul>';
          }
          echo '</li>';
      }
  }
@endphp

<ul class="nav nav-pills flex-column">
  @foreach($root_folders as $root_folder)
    <li class="nav-item">
      @if(isset($root_folder->children) && count($root_folder->children) > 0)
        <span class="toggle-icon" onclick="toggleSubdirectories('root-{{ $loop->index }}', this)">
                    <svg class="arrow-right" width="17px" height="17px" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#000000" fill-rule="evenodd" d="M5.29289,3.70711 C4.90237,3.31658 4.90237,2.68342 5.29289,2.29289 C5.68342,1.90237 6.31658,1.90237 6.70711,2.29289 L11.7071,7.29289 C12.0976,7.68342 12.0976,8.31658 11.7071,8.70711 L6.70711,13.7071 C6.31658,14.0976 5.68342,14.0976 5.29289,13.7071 C4.90237,13.3166 4.90237,12.6834 5.29289,12.2929 L9.58579,8 L5.29289,3.70711 Z"/>
                    </svg>
                    <svg class="arrow-down" width="17px" height="17px" viewBox="0 0 17 17" xmlns="http://www.w3.org/2000/svg">
                      <path fill="#000000" fill-rule="evenodd" d="M12.2929,5.292875 C12.6834,4.902375 13.3166,4.902375 13.7071,5.292875 C14.0976,5.683375 14.0976,6.316555 13.7071,6.707085 L8.70711,11.707085 C8.31658,12.097605 7.68342,12.097605 7.29289,11.707085 L2.29289,6.707085 C1.90237,6.316555 1.90237,5.683375 2.29289,5.292875 C2.68342,4.902375 3.31658,4.902375 3.70711,5.292875 L8,9.585765 L12.2929,5.292875 Z"/>
                    </svg>
                </span>
      @endif
      <a class="nav-link" href="#" data-type="0" data-path="{{ $root_folder->url }}">
        <i class="fa fa-folder fa-fw"></i> {{ $root_folder->name }}
      </a>
      @if(isset($root_folder->children) && count($root_folder->children) > 0)
        <ul id="root-{{ $loop->index }}" class="nav nav-pills flex-column subdir hidden">
          @php renderSubdirectories($root_folder->children, 1); @endphp
        </ul>
      @endif
    </li>
  @endforeach
</ul>

<script>
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
