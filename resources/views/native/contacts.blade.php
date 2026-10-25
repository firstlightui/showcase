<native:virtual-list
    class="w-full flex-1"
    :count="$total"
    :from="$virtualWindowFrom"
    :to="$virtualWindowTo"
    item="native.rows.contact"
    on-window-change="setVirtualWindow"
    estimated-row-height="72"
/>
