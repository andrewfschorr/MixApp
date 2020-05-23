export function getCsrfHeader() {
    return {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
};
