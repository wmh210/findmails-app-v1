@extends('layouts.layout')

@section('content')

<div class="main__container">
    <div class="credit__wrapper longer">
        <h1 class="text-3xl md:text-5xl font-extrabold text-center uppercase mb-12 bg-gradient-to-r from-indigo-400 via-purple-500 to-indigo-600 bg-clip-text text-transparent transform -rotate-2">Buy Credits</h1>
        @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
        @endif
        <form action="{{ route('stripe.store') }}" method="POST" id="card-form">
            @csrf
            <div class="mb-3">
                <label for="card-name" class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Your name</label>
                <input type="text" name="name" id="card-name" class="border-2 border-gray-200 h-11 px-4 rounded-xl w-full">
            </div>
            <div class="mb-3">
                <label for="email" class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Email</label>
                <input type="email" name="email" id="email" class="border-2 border-gray-200 h-11 px-4 rounded-xl w-full">
            </div>
            <div class="mb-3">
                <label for="card" class="inline-block font-bold mb-2 uppercase text-sm tracking-wider">Payment details</label>
                <div class="bg-gray-100 p-6 rounded-xl">
                    <div id="card"></div>
                </div>
            </div>
            <button type="submit" class="w-full bg-indigo-500 uppercase rounded-xl font-extrabold text-white px-6 h-12">SUBSCRIBE</button>
        </form>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe = Stripe("{{getenv('STRIPE_KEY')}}");
    const elements = stripe.elements();
    const cardElement = elements.create("card", {
        style: {
            base: {
                fontSize: "16px",
            },
        },
    });
    // var elements = stripe.elements({
    //     mode: 'payment',
    //     currency: 'usd',
    //     amount: 1099,
    // });
    // const cardElement = elements.create('payment');

    const cardForm = document.getElementById("card-form");
    const cardName = document.getElementById("card-name");
    cardElement.mount("#card");
    cardForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const {
            paymentMethod,
            error
        } = await stripe.createPaymentMethod({
            type: "card",
            card: cardElement,
            billing_details: {
                name: cardName.value,
            },
        });
        if (error) {
            console.log(error);
        } else {
            let input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "payment_method");
            input.setAttribute("value", paymentMethod.id);
            cardForm.appendChild(input);
            cardForm.submit();
        }
    });
</script>
@endsection