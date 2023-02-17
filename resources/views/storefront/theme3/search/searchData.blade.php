@if($total_row > 0)
    @foreach($data as $course)
        <li>
            <a class="list-link" href="{{route('store.viewcourse',[$store->slug,\Illuminate\Support\Facades\Crypt::encrypt($course->id)])}}">
                <i class="fas fa-search"></i>
                <span>{{$course->title}}</span>
            </a>
        </li>
    @endforeach
@else
    <tr>
        <td align="center" colspan="5">No Data Found</td>
    </tr>
@endif
