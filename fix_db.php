<?php
$a = \App\Models\AboutSection::where('slug', 'kymna')->first();
$lines = explode("\n", $a->description_ar);
$n = [];
foreach($lines as $l){
    $l=trim($l);
    if(!$l) continue;
    $p = explode(':', $l, 2);
    if(count($p)==2) {
        $n[]=trim($p[0]);
        $n[]=trim($p[1]);
    } else {
        $n[]=trim($l);
    }
}
$a->description_ar = implode("\n", $n);
$a->save();
echo "FIXED:\n" . $a->description_ar;
