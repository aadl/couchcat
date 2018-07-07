
/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */


$(function() {
    function numTracks() {
        return $('[id^=add-track-]').length + 1;
    };

    function appendTrack() {
        const trackCount = numTracks();
        const appendTarget = $('#track-add').parents('.form-group');

        // display the add-track button
        $('#track-add').css('display', 'initial');

        // create new row for form elements
        appendTarget.before($(document.createElement('div'))
                    .addClass('form-group row track-file-' + trackCount + ' track-' + trackCount)
        );
        const target = $('.track-file-' + trackCount);

        // add label and input for track upload
        target.append($(document.createElement('label'))
              .html('Track ' + trackCount + ' File')
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
        if ((matCode == 'z' || matCode == 'za')) {
            $('#attachment').parents('.form-group').addClass('no-display');
            $('#track-add').parents('.form-group').removeClass('no-display');
            $('#author').parents('.form-group').addClass('no-display');
            $('#artist').parents('.form-group').removeClass('no-display');
        } else {
            $('#attachment').parents('.form-group').removeClass('no-display');
            $('#track-add').parents('.form-group').addClass('no-display');
            $('#author').parents('.form-group').removeClass('no-display');
            $('#artist').parents('.form-group').addClass('no-display');
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

    $('.delete-game-code').click(function() {
        $(this).parents('.form-group').remove();
        return false;
    });

});
