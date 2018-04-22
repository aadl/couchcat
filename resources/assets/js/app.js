
/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */


$(function() {
    function numTracks() {
        return $('[id^=add-track-title]').length + 1;
    };

    function appendTrack() {
        const trackCount = numTracks();
        const appendTarget = $('#track-add');

        // display the add-track button
        $('#track-add').css('display', 'initial');

        // create new row for form elements
        appendTarget.before($(document.createElement('div'))
                    .addClass('form-group row track-title-' + trackCount + ' track-' + trackCount)
        );
        let target = $('.track-title-' + trackCount);

        // add label and input for track title
        target.append($(document.createElement('label'))
              .html('Track Title ' + trackCount)
              .addClass('col-sm-2 col-form-label')
              .attr('for', 'add-track-title-' + trackCount)
        );
        target.append($(document.createElement('div'))
              .addClass('col-sm-6')
        );
        target.children('div').append($(document.createElement('input')) 
              .addClass('form-control')
              .attr('id', 'add-track-title-' + trackCount)
              .attr('name', 'track-title[]')
              .attr('type', 'text')
        );

        // create new row for form elements
        appendTarget.before($(document.createElement('div'))
                    .addClass('form-group row track-file-' + trackCount + ' track-' + trackCount)
        );
        target = $('.track-file-' + trackCount);

        // add label and input for track upload
        target.append($(document.createElement('label'))
              .html('Track File ' + trackCount)
              .addClass('col-sm-2 col-form-label')
              .attr('for', 'add-track-' + trackCount)
        );
        target.append($(document.createElement('div'))
              .addClass('col-sm-6')
        );
        target.children('div').append($(document.createElement('input')) 
              .addClass('form-control form-group')
              .attr('id', 'add-track-' + trackCount)
              .attr('name', 'track-file[]')
              .attr('type', 'file')
        );

        // create delete button for track
        target.children('div').append($(document.createElement('button'))
              .html('Delete Track ' + trackCount)
              .addClass('btn btn-danger track-delete')
              .attr('data-target', '.track-' + trackCount)
        );
    }

    $('#mat_code').change(function() {
        const matCode = $(this).val();
        if ((matCode == 'z' || matCode == 'za') && numTracks() == 1) {
            appendTrack();
        }
    });

    $('#track-add').click(function() {
        appendTrack();
        return false;
    });

    $('form').on('click', '.track-delete', function() {
        const target = $(this).attr('data-target');
        $(target).fadeOut();
        return false;
    });

});
