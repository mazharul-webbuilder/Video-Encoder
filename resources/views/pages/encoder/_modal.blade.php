{{--Modal--}}
    <div class="modal fade" id="myModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="myModalLabel" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="myModalLabel">Setting</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="EncodingForm">
                        @csrf
                        <input type="hidden" name="video_ids" id="MultipleVideos" value="">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Preset*</label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="encoding_resolution[]" class="form-control"  id="mySelect" style="width: 100%;" multiple="multiple" required>
                                        <option value="1440p">_1440_ (H:1440 * W:2080) _Bitrate: 5096 </option>
                                        <option value="1080p">_1080_ (H:1080 * W:1920) _Bitrate: 4096 </option>
                                        <option value="720p">_720_ (H:720 * W:1280) _Bitrate: 2048 </option>
                                        <option value="360p">_360_ (H:360 * W:640) _Bitrate: 276 </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="">Path</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="destination_path" id="encodingPath" class="form-control"  placeholder="Ex: movies" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="EncodingSubmitBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    {{--End Modal--}}
