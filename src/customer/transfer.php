<?php


    ob_start();
    include('includes/header.php');
    use App\classes\Customer;
    $customer = new Customer();
    
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $transfer = $customer->transfer($_POST['amount'], $session->get('user'), $_POST['email']);
        
        if ($transfer) {
            if(is_array($transfer)){
                foreach ($transfer as $key => $data) {
                    foreach ($data as $key => $value) {
                        if($key === 'error'){
                            $errors[$data['field']] = $value;
                        }
                    }
                }
            }
            if (empty($errors)) {
                $user->flash('success', 'Transfer successful');
            }
            
        } else {
            $user->flash('error', 'Somthing went wrong, please try again');
        }
        header('Location: transfer.php');
        exit();
    }
    ob_end_flush();
?>
            <header class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold tracking-tight text-white">
                        Transfer Balance
                    </h1>
                </div>
            </header>
        </div>

        <main class="-mt-32">
            <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg p-2">
                <?php
                $successMessage = $user->flash('success');
                $errorMessage = $user->flash('error');
                if ($successMessage) : ?>
                    <div class="mt-2 mb-4 bg-teal-100 border border-teal-200 text-sm text-center text-teal-800 rounded-lg p-4" role="alert">
                        <span class="font-bold"><?= $successMessage; ?></span>
                    </div>
                <?php endif; if ($errorMessage) : ?>
                    <div class="mt-2 mb-4 bg-red-100 border border-red-200 text-sm text-center text-red-800 rounded-lg p-4" role="alert">
                        <span class="font-bold"><?= $errorMessage; ?></span>
                    </div>
                <?php endif; ?>
                    <!-- Current Balance Stat -->
                    <dl
                        class="mx-auto grid grid-cols-1 gap-px sm:grid-cols-2 lg:grid-cols-4">
                        <div
                            class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
                            <dt class="text-sm font-medium leading-6 text-gray-500">
                                Current Balance
                            </dt>
                            <dd
                                class="w-full flex-none text-3xl font-medium leading-10 tracking-tight text-gray-900">
                                $<?= number_format($balance, 2); ?>
                            </dd>
                        </div>
                    </dl>

                    <hr />
                    <!-- Transfer Form -->
                    <div class="sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="mt-4 text-sm text-gray-500">
                                <form
                                    action="transfer.php"
                                    method="POST">
                                    <!-- Recipient's Email Input -->
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="block w-full ring-0 outline-none py-2 text-gray-800 border-b placeholder:text-gray-400 md:text-4xl"
                                        placeholder="Recipient's Email Address"/>
                                        <?php if (isset($errors['email'])) : ?>
                                            <p class="text-xs text-red-600 mt-2"><?= $errors['email']; ?></p>
                                        <?php endif; ?>


                                    <!-- Amount -->
                                    <div class="relative mt-4 md:mt-8">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-0">
                                            <span class="text-gray-400 md:text-4xl">$</span>
                                        </div>
                                        <input
                                            type="number"
                                            name="amount"
                                            id="amount"
                                            class="block w-full ring-0 outline-none pl-4 py-2 md:pl-8 text-gray-800 border-b border-b-emerald-500 placeholder:text-gray-400 md:text-4xl"
                                            placeholder="0.00"/>
                                            <?php if (isset($errors['amount'])) : ?>
                                                <p class="text-xs text-red-600 mt-2"><?= $errors['amount']; ?></p>
                                            <?php endif; ?>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="mt-5">
                                        <button
                                            type="submit"
                                            class="w-full px-6 py-3.5 text-base font-medium text-white bg-emerald-600 hover:bg-emerald-800 focus:ring-4 focus:outline-none focus:ring-emerald-300 rounded-lg md:text-xl text-center">
                                            Proceed
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>