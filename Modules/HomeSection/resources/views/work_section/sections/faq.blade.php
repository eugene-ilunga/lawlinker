<div class="tab-pane fade pt-0" id="faq_tab" role="tabpanel">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>{{ __('Faqs') }}</h4>
            @if (checkAdminHasPermission('work.section.faq.store'))
                <div>
                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#create_faq" class="btn btn-primary"><i
                            class="fas fa-plus"></i> {{ __('Add New') }}</a>
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%">{{ __('SN') }}</th>
                            <th width="25%">{{ __('Question') }}</th>
                            <th width="30%">{{ __('Answer') }}</th>
                            <th width="20%">{{ __('Status') }}</th>
                            <th width="20%">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faqs as $item)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $item?->getTranslation($code)?->question }}</td>
                                <td>{{ Str::limit($item?->getTranslation($code)?->answer, 20) }}
                                </td>
                                <td>
                                    <input onchange="changeStatus({{ $item->id }})" id="status_toggle"
                                        type="checkbox" {{ $item->status ? 'checked' : '' }} data-toggle="toggle"
                                        data-onlabel="{{ __('Active') }}" data-offlabel="{{ __('Inactive') }}"
                                        data-onstyle="success" data-offstyle="danger">
                                </td>
                                <td>
                                    @if (checkAdminHasPermission('work.section.faq.update'))
                                        <a href="javascript:;" data-bs-toggle="modal"
                                            data-bs-target="#edit_faq_id_{{ $item->id }}"
                                            class="btn btn-warning btn-sm"><i class="fa fa-edit"
                                                aria-hidden="true"></i></a>
                                    @endif
                                    @if (checkAdminHasPermission('work.section.faq.delete'))
                                        <x-admin.delete-button :id="$item->id" onclick="deleteData" />
                                    @endif
                            </tr>
                        @empty
                            <x-empty-table :name="__('Item')" route="admin.work-section.index" create="no"
                                :message="__('No data found!')" colspan="7" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="float-right">
                {{ $faqs->onEachSide(0)->links() }}
            </div>
        </div>
    </div>
</div>