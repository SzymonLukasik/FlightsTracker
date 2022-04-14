<form class="flight-data" method="post" id="pages_form" action="">
    <button type="submit" name="page" value = <?= $this->data['pages']['first']?> >First</button>
    <button type="submit" name="page" value = <?= $this->data['pages']['prev']?> >Previous</button>
    <h3> Page <?= $this->data['pages']['current'] ?> out of <?= $this->data['pages']['last'] ?> </h3>
    <button type="submit" name="page" value = <?= $this->data['pages']['next']?> >Next</button>
    <button type="submit" name="page" value = <?= $this->data['pages']['last']?> >Last</button>
</form>