<?php
$link_limit = 10;

?>
@if ($paginator->lastPage() > 1)
    <div style="overflow: hidden;">
        <ul class="pagination">
            <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                <a class="prew" href="{{ $paginator->url(1) }}">
                    <img src="/img/prew.png" alt="prew">
                    <span>Назад</span>
                </a>
            </li>
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <?php
                $half_total_links = floor($link_limit / 2);
                $from = $paginator->currentPage() - $half_total_links;
                $to = $paginator->currentPage() + $half_total_links;
                if ($paginator->currentPage() < $half_total_links) {
                    $to += $half_total_links - $paginator->currentPage();
                }
                if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                }
                ?>
                @if ($from < $i && $i < $to)
                    <li>
                        <a  class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor
            <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                <a class="next" href="{{ $paginator->url($paginator->lastPage()) }}">
                    <span>Вперед</span> <img src="/img/next.png" alt="next">
                </a>
            </li>
        </ul>
    </div>
@endif
