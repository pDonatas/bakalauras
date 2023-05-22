<!-- Main Footer -->
<footer class="main-footer">
    <strong>Copyright &copy; 2023-{{ date("Y") }} <a href="{{ config("app.url") }}">{{ config("app.name") }}</a>.</strong> {{ __('All rights reserved.') }}
</footer>

<div id="deleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100">
                    <div class="icon-box">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    {{ __('Are you sure?') }}
                </h4>
            </div>
            <div class="modal-body">
                <p>{{ __('Do you really want to delete these records? This process cannot be undone.') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- OPTIONAL SCRIPTS -->

<!-- overlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@1.13.1/js/OverlayScrollbars.min.js" integrity="sha256-7mHsZb07yMyUmZE5PP1ayiSGILxT6KyU+a/kTDCWHA8=" crossorigin="anonymous"></script>

<!-- REQUIRED SCRIPTS -->

<!-- AdminLTE App -->
<script src="{{ asset('/assets/admin/js/adminlte.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script>
    const SELECTOR_SIDEBAR = '.sidebar'
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave'
    }
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof OverlayScrollbars !== 'undefined') {
            OverlayScrollbars(document.querySelectorAll(SELECTOR_SIDEBAR), {
                className: Default.scrollbarTheme,
                sizeAutoCapable: true,
                scrollbars: {
                    autoHide: Default.scrollbarAutoHide,
                    clickScrolling: true
                }
            })
        }
    })
</script>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js" integrity="sha256-7lWo7cjrrponRJcS6bc8isfsPDwSKoaYfGIHgSheQkk=" crossorigin="anonymous"></script>

<script src="https://cdn.tiny.cloud/1/h60jy9aigdsikubgsdndpy2xsz8tbufbi44e2vrsbqccw3n3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

<script>
        <!-- Delete modal -->
        function deleteItem(url) {
            $('#deleteModal').modal('show');
            $('#deleteModal .btn-danger').click(function () {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function () {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    }
                });
            });
        }
</script>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat | code',
        tinycomments_mode: 'embedded',
        tinycomments_author: '{{ auth()->user()->name }}',
        mergetags_list: [
            { value: '{{ auth()->user()->name }}', title: 'First Name' },
            { value: '{{ auth()->user()->email }}', title: 'Email' },
        ]
    });
</script>

@yield('scripts')
