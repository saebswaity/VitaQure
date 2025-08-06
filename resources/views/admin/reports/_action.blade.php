<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-cog"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @can('edit_report')
        <a class="dropdown-item" href="{{route('admin.reports.edit',$group['id'])}}">
            <i class="fa fa-flask" aria-hidden="true"></i>
            {{__('Edit Report')}}
        </a>
        @endcan
        @can('sign_report')
        <a class="dropdown-item" href="{{route('admin.reports.sign',$group['id'])}}">
            <i class="fas fa-signature" aria-hidden="true"></i>
            {{__('Sign Report')}}
        </a>
        @endcan
        @can('view_report')
        <a class="dropdown-item" href="{{route('admin.reports.show',$group['id'])}}">
            <i class="fa fa-eye" aria-hidden="true"></i>
            {{__('Show')}}
        </a>
        <a style="cursor: pointer" data-toggle="modal" data-target="#print_barcode_modal" class="dropdown-item print_barcode" group_id="{{$group['id']}}">
            <i class="fa fa-barcode" aria-hidden="true"></i>
            {{__('Print barcode')}}
        </a>
                @if($whatsapp['report']['active']&&isset($group['report_pdf']))

        <a target="_blank" href="{{whatsapp_notification($group,'report')}}" class="dropdown-item">
            <i class="fab fa-whatsapp" aria-hidden="true" class="text-success"></i>
            {{__('Send Report')}}
        </a>
        @endif
        @if($email['report']['active']&&isset($group['report_pdf']))
        <form action="{{route('admin.reports.send_report_mail',$group['id'])}}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item">
                <i class="fa fa-envelope" aria-hidden="true" class="text-success"></i>
                {{__('Send Report')}}
            </button>
        </form>
        @endif
        @endcan
    </div>
</div>