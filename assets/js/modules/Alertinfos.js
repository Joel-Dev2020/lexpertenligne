module.exports = function(type = 'success', message){
    return '<div class="alert alert-'+type+' border-1 border-left-4 border-left-'+type+'" role="alert">\n' +
        '        <div class="d-flex flex-wrap align-items-start">\n' +
        '            <div class="mr-8pt">\n' +
        '                <i class="material-icons">access_time</i>\n' +
        '            </div>\n' +
        '            <div class="flex" style="min-width: 180px">\n' +
        '                <small class="text-black-100">'+message+'</small>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '    <br>'
};


