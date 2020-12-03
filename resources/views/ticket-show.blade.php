<div class="ui grid mx2 segment segment-hover">
    <div class="ui form sixteen wide column">
        <div class="inline fields">
            <label for="search">Customer Name: </label>
            <p>{{$ticket->customer_name}}</p>
        </div>

        <div class="inline fields">
            <label for="search">Description: </label>
            <p>{{$ticket->description}}</p>
        </div>

        <div class="inline fields">
            <label for="search">Email: </label>
            <p>{{$ticket->email}}</p>
        </div>

        <div class="inline fields">
            <label for="search">Phone: </label>
            <p>{{$ticket->phone}}</p>
        </div>

        <div class="inline fields">
            <label for="search">Agent Reply: </label>
            <p>{{$ticket->reply->reply}}</p>
        </div>
    </div>
</div>
