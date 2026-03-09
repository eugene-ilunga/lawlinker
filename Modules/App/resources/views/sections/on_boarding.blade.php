<div class="tab-pane fade active show pt-0" id="on_boarding_tab" role="tabpanel">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>
                <x-admin.add-button :href="route('admin.app.screen.create')" />
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive max-h-400">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%">{{ __('SN') }}</th>
                            <th width="10%">{{ __('Image') }}</th>
                            <th width="15%">{{ __('Title') }}</th>
                            <th width="30%">{{ __('Short Description') }}</th>
                            <th width="10%">{{ __('Order number') }}</th>
                            <th width="15%">{{ __('Status') }}</th>
                            <th width="15%">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($screens as $screen)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td><img src="{{ asset($screen?->image) }}" alt="{{ $screen->title }}" class="rounded-circle my-2"></td>
                                <td>{{ $screen->title }}</td>
                                <td>{{ Str::limit($screen->sort_description, 30, '...')}}</td>
                                <td>{{ $screen->order }}</td>
                                <td>
                                    <input onchange="changeStatus({{ $screen->id }})"
                                        id="status_toggle" type="checkbox"
                                        {{ $screen->status ? 'checked' : '' }} data-toggle="toggle"
                                        data-onlabel="{{ __('Active') }}"
                                        data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                        data-offstyle="danger">
                                </td>
                                <td>
                                    <x-admin.edit-button :href="route('admin.app.screen.edit', [
                                        'on_boarding_screen' => $screen->id,
                                        'code' => getSessionLanguage(),
                                    ])" />
                                    <x-admin.delete-button :id="$screen->id" onclick="deleteData" />
                                </td>
                            </tr>
                        @empty
                            <x-empty-table :name="__('On Boarding Screens')" route="admin.app.screen.create"
                                create="yes" :message="__('No data found!')" colspan="7" />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (request()->get('par-page') !== 'all')
                <div class="float-right">
                    {{ $screens->onEachSide(0)->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
