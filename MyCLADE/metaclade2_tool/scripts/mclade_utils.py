import sys

def eprint( *args, **kwargs ):
    print(*args, file=sys.stderr, **kwargs)

def overlap( a_range, b_range ):
    (a_beg, a_end) = a_range
    (b_beg, b_end) = b_range
    # it is assumed that a "comes before" than b
    if a_beg > b_beg or (a_beg == b_beg and a_end > b_end):
        return overlap(b_range,a_range)
    a_len = a_end-a_beg+1
    b_len = b_end-b_beg+1
    overlap_len  = a_end-b_beg+1
    overlap_pmin = overlap_len/float(min(a_len,b_len))
    overlap_pmax = overlap_len/float(max(a_len,b_len))
    return (overlap_len,overlap_pmin,overlap_pmax)

