{foreach from=$trackings item=tracking}
<iframe src="https://lb.affilae.com/?key={$tracking.code}&amp;id={$tracking.id}&amp;amount={$tracking.total}&amp;payment={$tracking.payment}&amp;customer={$tracking.customerId}" frameborder="0" width="1" height="1"></iframe>
{/foreach}

